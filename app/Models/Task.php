<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_url',
        'is_active',
        'amount',
        'task_code',
        'remaining_code'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'float',
    ];
}
