<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\FraudDetectable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, FraudDetectable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ref_id',
        'phone_code',
        'phone',
        'ref_by',
        'username',
        'code',
        'ip',
        'balance',
        'register_bonus',
        'withdraw_password',
        'pix_type',
        'pix_key'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'balance' => 'float',
        'ref_id' => 'integer',
        'ref_by' => 'integer',
        'status' => 'string',
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function ledgers()
    {
        return $this->hasMany(UserLedger::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function commissions()
    {
        return $this->hasMany(UserLedger::class, 'user_id')
            ->where('reason', 'LIKE', '%commission%');
    }

    public function investments()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(UserLedger::class);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by', 'ref_id');
    }

    // Nível 1 - Diretos
    public function levelOneReferrals()
    {
        return $this->hasMany(User::class, 'ref_by', 'ref_id');
    }

    // Nível 2 - Indicações dos diretos
    public function levelTwoReferrals()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'ref_by',     // foreign key on intermediate (1º nível)
            'ref_by',     // foreign key on final (2º nível)
            'ref_id',     // local key on parent
            'ref_id'      // local key on intermediate
        );
    }

    public function getNetworkAttribute()
    {
        $network = collect();

        $this->load('referrals'); // garante que o relacionamento esteja carregado

        $this->buildNetwork($this, $network, 1);

        return $network;
    }

    private function buildNetwork(User $user, Collection &$network, int $level)
    {
        if ($level > 3) {
            return;
        }

        foreach ($user->referrals as $referral) {
            $referral->nivel = $level;
            $network->push($referral);

            $referral->load('referrals'); // carrega referrals do referral
            $this->buildNetwork($referral, $network, $level + 1);
        }
    }

    // Nível 3 - Indicações do segundo nível
    public function levelThreeReferrals()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'ref_by', // fk em nível 2
            'ref_by', // fk em nível 3
            'ref_id', // ref atual
            'ref_id'  // ref intermediário
        )->join('users as u2', 'users.ref_by', '=', 'u2.ref_id')
            ->join('users as u3', 'u2.ref_by', '=', 'u3.ref_id')
            ->where('u3.ref_by', $this->ref_id)
            ->select('users.*');
    }

    public function getReferralCounts()
    {
        $level1 = User::where('ref_by', $this->ref_id)->get();
        $level2 = collect();
        $level3 = collect();

        foreach ($level1 as $l1) {
            $children = User::where('ref_by', $l1->ref_id)->get();
            $level2 = $level2->merge($children);

            foreach ($children as $l2) {
                $level3 = $level3->merge(
                    User::where('ref_by', $l2->ref_id)->get()
                );
            }
        }

        return [
            'level_1' => $level1->count(),
            'level_2' => $level2->count(),
            'level_3' => $level3->count(),
        ];
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    /**
     * Diminui o saldo do usuário.
     *
     * @param int $amountCents
     */
    public function subtractBalance(int $amountCents): void
    {
        $this->balance -= $amountCents;
        $this->save();
    }

    /**
     * Relacionamento para o usuário que indicou este usuário (o "referrer").
     *
     * @return BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ref_by', 'ref_id');
    }

    /**
     * Processa a comissão de indicação para até 5 níveis.
     *
     * @param float $amount O valor base para o cálculo da comissão.
     */
    public function processComissionReferral(float $amount, string $description = 'Comissão de indicação do nível')
    {
        // Pega as taxas de comissão do modelo Rebate. Assumimos que há uma única entrada.
        $rebateRates = Rebate::first();

        Log::info('[DADOS REBATE]: ' . json_encode($rebateRates, JSON_PRETTY_PRINT));

        // Se não houver taxas de comissão, não há o que processar.
        if (!$rebateRates) {
            return;
        }

        $referrer = $this->referrer;
        $currentLevel = 1;
        Log::info('[VALOR DO INVESTIMENTO] USER: ' . $amount);


        // Itera sobre os níveis de indicação, até 3 níveis ou até que não haja mais um referrer.
        while ($referrer && $currentLevel <= 3) {
            // Constrói o nome da coluna dinamicamente, ex: 'interest_commission1'
            $commissionKey = 'interest_commission' . $currentLevel;
            $commissionRate = $rebateRates->{$commissionKey} ?? 0;

            if ($commissionRate > 0) {
                // Calcula o valor da comissão e converte para centavos.
                $commissionAmount = $amount * ($commissionRate / 100);

                Log::info('[PROCESSANDO COMISSÃO] USER: ' . $referrer->id . ' VALOR: ' .  (float) $commissionAmount);

                // Aumenta o saldo do referrer.
                $referrer->addBalance($commissionAmount);


                // Atualiza o total de comissão do referrer.
                $referrer->total_commission += $commissionAmount;
                $referrer->save();

                // Registra a comissão no ledger do usuário para auditoria.
                $referrer->ledgers()->create([
                    'reference_type' => 'commission',
                    'get_balance_from_user_id' => $this->id,
                    'credit' => $commissionAmount,
                    'debit' => 0,
                    'date' => now(),
                    'step' => $currentLevel,
                    'status' => 'approved',
                    'reason' => "commission_indication",
                    'perticulation' => "Comissão de indicação do nível {$currentLevel} de " . $this->name ?? $this->phone,
                    'amount' => $commissionAmount,
                ]);
            }

            // Move para o próximo nível de referrer.
            $referrer = $referrer->referrer;
            $currentLevel++;
        }
    }

    /**
     * Aumenta o saldo do usuário.
     *
     * @param int $amountCents
     */
    public function addBalance(int $amountCents): void
    {
        $this->balance += (float) $amountCents;
        $this->save();
    }
}
