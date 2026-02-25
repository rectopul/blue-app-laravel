<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alert_type',
        'risk_score',
        'description',
        'data',
        'status',
        'resolved_at',
        'resolved_by',
        'notes'
    ];

    protected $casts = [
        'data' => 'array',
        'resolved_at' => 'datetime',
        'risk_score' => 'integer'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_INVESTIGATING = 'investigating';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_FALSE_POSITIVE = 'false_positive';

    const ALERT_TYPES = [
        'NO_DEPOSIT_WITHDRAWAL' => 'Saque sem depósito',
        'SUSPICIOUS_IP_PATTERN' => 'Padrão suspeito de IP',
        'RAPID_REFERRAL_CREATION' => 'Criação rápida de indicados',
        'UNUSUAL_WITHDRAWAL_PATTERN' => 'Padrão incomum de saques',
        'BALANCE_MANIPULATION' => 'Manipulação de saldo',
        'FAKE_REFERRAL_NETWORK' => 'Rede de indicação falsa',
        'MULTIPLE_ACCOUNTS_SAME_DATA' => 'Múltiplas contas com dados similares',
        'SUSPICIOUS_INVESTMENT_PATTERN' => 'Padrão suspeito de investimentos'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
