<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'get_balance_from_user_id',
        'reason',
        'perticulation',
        'amount',
        'debit',
        'credit',
        'status',
        'date',
        'step',
        'created_at',
        'updated_at',
        'reference_id',
        'reference_type',
        'metadata'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'get_balance_from_user_id' => 'integer',
        'amount' => 'float',
        'reason' => 'string',
        'reference_id' => 'integer',
        'reference_type' => 'string',
        'step' => 'integer',
        'status' => 'string',
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gera a descrição (perticulation) de acordo com o motivo
     */
    public static function generatePerticulation(string $reason, $amount): string
    {
        $now = Carbon::now()->format('d/m/Y H:i');

        return match ($reason) {
            'deposit'  => "Deposit of {$amount} on {$now}",
            'withdraw' => "Withdrawal of {$amount} on {$now}",
            'bonus'    => "Bonus credited on {$now}",
            'daily_income'   => "Profit earned on {$now}",
            default    => "Transaction on {$now}",
        };
    }
}
