<?php

namespace App\Modules\Gamification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserGamificationEgg extends Model
{
    use HasFactory;

    protected $table = 'user_gamification_eggs';

    protected $fillable = [
        'user_id',
        'gamification_setting_id',
        'collected_at',
    ];

    protected $casts = [
        'collected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setting()
    {
        return $this->belongsTo(GamificationSetting::class, 'gamification_setting_id');
    }
}
