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
        'pix_key',
        'last_task_completed_at'
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
        'updated_at' => 'datetime',
        'last_task_completed_at' => 'datetime'
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

    // NÃ­vel 1 - Diretos
    public function levelOneReferrals()
    {
        return $this->hasMany(User::class, 'ref_by', 'ref_id');
    }

    // NÃ­vel 2 - IndicaÃ§Ãµes dos diretos
    public function levelTwoReferrals()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'ref_by',     // foreign key on intermediate (1Âº nÃ­vel)
            'ref_by',     // foreign key on final (2Âº nÃ­vel)
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

    // NÃ­vel 3 - IndicaÃ§Ãµes do segundo nÃ­vel
    public function levelThreeReferrals()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'ref_by', // fk em nÃ­vel 2
            'ref_by', // fk em nÃ­vel 3
            'ref_id', // ref atual
            'ref_id'  // ref intermediÃ¡rio
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

    public function taskCompletions()
    {
        return $this->hasMany(UserTaskCompletion::class);
    }

    /**
     * Diminui o saldo do usuÃ¡rio.
     *
     * @param int $amountCents
     */
    public function subtractBalance(float $amount): void
    {
        $this->balance -= $amount;
        $this->save();
    }

    /**
     * Relacionamento para o usuÃ¡rio que indicou este usuÃ¡rio (o "referrer").
     *
     * @return BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ref_by', 'ref_id');
    }

    /**
     * Processa a comissÃ£o de indicaÃ§Ã£o para atÃ© 5 nÃ­veis.
     *
     * @param float $amount O valor base para o cÃ¡lculo da comissÃ£o.
     */
    public function processComissionReferral(float $amount, string $description = 'ComissÃ£o de indicaÃ§Ã£o do nÃ­vel')
    {
        // Pega as taxas de comissÃ£o do modelo Rebate. Assumimos que hÃ¡ uma Ãºnica entrada.
        $rebateRates = Rebate::first();

        Log::info('[DADOS REBATE]: ' . json_encode($rebateRates, JSON_PRETTY_PRINT));

        // Se nÃ£o houver taxas de comissÃ£o, nÃ£o hÃ¡ o que processar.
        if (!$rebateRates) {
            return;
        }

        $referrer = $this->referrer;
        $currentLevel = 1;
        Log::info('[VALOR DO INVESTIMENTO] USER: ' . $amount);


        // Itera sobre os nÃ­veis de indicaÃ§Ã£o, atÃ© 3 nÃ­veis ou atÃ© que nÃ£o haja mais um referrer.
        while ($referrer && $currentLevel <= 3) {
            // ConstrÃ³i o nome da coluna dinamicamente
            $commissionKey = match ($currentLevel) {
                1 => 'first_level_percentage',
                2 => 'second_level_percentage',
                3 => 'third_level_percentage',
                default => null,
            };
            $commissionRate = $rebateRates->{$commissionKey} ?? 0;

            if ($commissionRate > 0) {
                // Calcula o valor da comissÃ£o e converte para centavos.
                $commissionAmount = $amount * ($commissionRate / 100);

                Log::info('[PROCESSANDO COMISSÃƒO] USER: ' . $referrer->id . ' VALOR: ' .  (float) $commissionAmount);

                // Aumenta o saldo do referrer.
                $referrer->addBalance($commissionAmount);


                // Atualiza o total de comissÃ£o do referrer.
                $referrer->total_commission += $commissionAmount;
                $referrer->save();

                // Registra a comissÃ£o no ledger do usuÃ¡rio para auditoria.
                $referrer->ledgers()->create([
                    'reference_type' => 'commission',
                    'get_balance_from_user_id' => $this->id,
                    'credit' => $commissionAmount,
                    'debit' => 0,
                    'date' => now(),
                    'step' => $currentLevel,
                    'status' => 'approved',
                    'reason' => "commission_indication",
                    'perticulation' => "ComissÃ£o de indicaÃ§Ã£o do nÃ­vel {$currentLevel} de " . $this->name ?? $this->phone,
                    'amount' => $commissionAmount,
                ]);
            }

            // Move para o prÃ³ximo nÃ­vel de referrer.
            $referrer = $referrer->referrer;
            $currentLevel++;
        }
    }

    /**
     * Aumenta o saldo do usuÃ¡rio.
     *
     * @param int $amountCents
     */
    public function addBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }
}

