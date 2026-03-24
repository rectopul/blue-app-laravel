<?php

namespace Tests\Unit\Gamification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Package;
use App\Modules\Gamification\Models\GamificationSetting;
use App\Modules\Gamification\Models\UserGamificationEgg;
use App\Modules\Gamification\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class GamificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GamificationService();
    }

    public function test_get_eligible_eggs_returns_empty_if_no_active_purchase()
    {
        $user = User::factory()->create([
            'ref_by' => '0',
            'ref_id' => '111',
        ]);

        // No purchase created

        $eggs = $this->service->getEligibleEggs($user);

        $this->assertCount(0, $eggs);
    }

    public function test_get_eligible_eggs_returns_eggs_based_on_referrals()
    {
        $user = User::factory()->create([
            'ref_by' => '0',
            'ref_id' => '12345'
        ]);

        Package::create([
            'id' => 1,
            'name' => 'Test',
            'title' => 'Test',
            'photo' => 'test.png',
            'price' => 100,
            'code' => \Illuminate\Support\Str::uuid(),
            'validity' => 30,
            'status' => 'active'
        ]);

        // Active purchase
        Purchase::create([
            'user_id' => $user->id,
            'package_id' => 1,
            'amount' => 100,
            'status' => 'active',
            'date' => date('Y-m-d'),
        ]);

        // Settings
        GamificationSetting::create([
            'required_referrals' => 2,
            'page_name' => 'test.page',
            'bonus_reward' => 50,
            'is_active' => true
        ]);

        // 1 Referral (not enough)
        User::factory()->create([
            'ref_by' => '12345',
            'ref_id' => '6789'
        ]);

        $eggs = $this->service->getEligibleEggs($user);
        $this->assertCount(0, $eggs);

        // Clear cache to re-count
        Cache::flush();

        // 2 Referrals (enough)
        User::factory()->create([
            'ref_by' => '12345',
            'ref_id' => '6790'
        ]);

        $eggs = $this->service->getEligibleEggs($user);
        $this->assertCount(1, $eggs);
    }

    public function test_get_eligible_eggs_excludes_collected_ones()
    {
        $user = User::factory()->create([
            'ref_by' => '0',
            'ref_id' => '12345'
        ]);

        Package::create([
            'id' => 1,
            'name' => 'Test',
            'title' => 'Test',
            'photo' => 'test.png',
            'price' => 100,
            'code' => \Illuminate\Support\Str::uuid(),
            'validity' => 30,
            'status' => 'active'
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'package_id' => 1,
            'status' => 'active',
            'date' => date('Y-m-d'),
        ]);

        $setting = GamificationSetting::create([
            'required_referrals' => 1,
            'page_name' => 'test.page',
            'bonus_reward' => 50,
            'is_active' => true
        ]);

        User::factory()->create([
            'ref_by' => '12345',
            'ref_id' => '6791'
        ]);

        // Mark as collected
        UserGamificationEgg::create([
            'user_id' => $user->id,
            'gamification_setting_id' => $setting->id,
        ]);

        $eggs = $this->service->getEligibleEggs($user);
        $this->assertCount(0, $eggs);
    }
}
