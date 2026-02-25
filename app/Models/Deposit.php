<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'webhook_data',
        'method_name',
        'address',
        'transaction_id',
        'order_id',
        'amount',
        'security_hash',
        'ip_address',
        'user_agent',
        'webhook_data',
        'date',
        'status',
    ];

    protected $casts = [
        'user_id'        => 'integer',
        'transaction_id' => 'string',
        'amount'         => 'decimal:2', // valor monetário
        'date'           => 'datetime',  // se for um campo datetime/timestamp
        'status'         => TransactionStatus::class,
    ];


    protected $hidden = [
        'transaction_id',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateTransactionId(): string
    {
        $data = random_bytes(16);

        // Ajusta os bits para versão e variante conforme o padrão UUID v4
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // versão 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variante RFC 4122

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
