<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTaskCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'reward_amount',
        'completion_date',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'reward_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
