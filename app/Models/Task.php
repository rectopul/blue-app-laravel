<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'watch_seconds',
        'sort_order',
        'icon',
        'is_active',
        'amount',
        'task_code',
        'remaining_code'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'float',
        'watch_seconds' => 'integer',
        'sort_order' => 'integer',
    ];
}
