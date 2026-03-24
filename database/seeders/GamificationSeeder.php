<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Gamification\Models\GamificationSetting;

class GamificationSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['required_referrals' => 10, 'page_name' => 'user.team', 'bonus_reward' => 50.00, 'is_active' => true],
            ['required_referrals' => 20, 'page_name' => 'user.personal-details', 'bonus_reward' => 100.00, 'is_active' => true],
            ['required_referrals' => 30, 'page_name' => 'packages.list', 'bonus_reward' => 150.00, 'is_active' => true],
            ['required_referrals' => 40, 'page_name' => 'dashboard', 'bonus_reward' => 200.00, 'is_active' => true],
            ['required_referrals' => 50, 'page_name' => 'transactions.history', 'bonus_reward' => 250.00, 'is_active' => true],
        ];

        foreach ($settings as $setting) {
            GamificationSetting::updateOrCreate(
                ['required_referrals' => $setting['required_referrals']],
                $setting
            );
        }
    }
}
