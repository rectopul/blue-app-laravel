<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_expire_at',
        'token_created_at',
        'gateway_token',
        'min_deposit',
        'active_gateway',
        'bitflow_client_id',
        'bitflow_client_secret',
        'bitflow_public_key'
    ];

    protected $casts = [
        'min_deposit' => 'float',
    ];
}
