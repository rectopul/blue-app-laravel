<?php

namespace App\Modules\Gamification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationSetting extends Model
{
    use HasFactory;

    protected $table = 'gamification_settings';

    protected $fillable = [
        'required_referrals',
        'page_name',
        'bonus_reward',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'bonus_reward' => 'decimal:2',
    ];
}
