<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'daily_income',
        'date',
        'status', // active, inactive, pending
        'validity',
        'purchased_at',
        'expires_at',
        'transaction_hash'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'package_id' => 'integer',
        'amount' => 'float',
        'status' => 'string',
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessor para rendimento total até hoje
    public function getTotalIncomeAttribute()
    {
        if (!$this->purchased_at) {
            return 0;
        }

        $hoje = Carbon::today();
        $inicio = $this->purchased_at->copy();
        $fim = $this->expires_at ?? $hoje;

        // calcula dias entre compra e hoje (ou expiração, o que for menor)
        $dias = $inicio->diffInDays(min($fim, $hoje)) + 1;

        return $dias * $this->daily_income;
    }

    /**
     * Gera o hash único da transação
     */
    public function generateHash()
    {
        $secret = Config::get('app.key'); // ou crie sua própria chave no .env
        $data = $this->id . $this->user_id . $this->amount . $this->created_at;
        return hash('sha256', $data . $secret);
    }

    /**
     * Valida se o hash atual é válido
     */
    public function validateHash()
    {
        return $this->hash === $this->generateHash();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
