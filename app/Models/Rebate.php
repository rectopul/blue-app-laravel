<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rebate extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_level_percentage',
        'second_level_percentage',
        'third_level_percentage',
        'status',
    ];

    protected $casts = [
        'first_level_percentage' => 'integer',
        'second_level_percentage' => 'integer',
        'third_level_percentage' => 'integer',
    ];
}
