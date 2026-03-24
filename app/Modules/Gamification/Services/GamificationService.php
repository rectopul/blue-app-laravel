<?php

namespace App\Modules\Gamification\Services;

use App\Models\User;
use App\Models\Purchase;
use App\Modules\Gamification\Models\GamificationSetting;
use App\Modules\Gamification\Models\UserGamificationEgg;
use Illuminate\Support\Facades\Cache;

class GamificationService
{
    /**
     * Get eligible eggs for the user.
     * Use Cache (Remember) for 10 minutes for referral count.
     *
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public function getEligibleEggs(User $user)
    {
        // Check if user has at least one active purchase
        $hasActivePurchase = Purchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if (!$hasActivePurchase) {
            return collect();
        }

        // Cache referral count for 10 minutes
        $referralCount = Cache::remember("user_{$user->id}_referral_count", 600, function () use ($user) {
            return $user->levelOneReferrals()->count();
        });

        // Get active settings where the user has enough referrals
        $eligibleSettings = GamificationSetting::where('is_active', true)
            ->where('required_referrals', '<=', $referralCount)
            ->get();

        // Filter out eggs already collected by the user
        $collectedEggIds = UserGamificationEgg::where('user_id', $user->id)
            ->pluck('gamification_setting_id')
            ->toArray();

        return $eligibleSettings->reject(function ($setting) use ($collectedEggIds) {
            return in_array($setting->id, $collectedEggIds);
        });
    }

    /**
     * Get the egg for a specific page name if eligible.
     *
     * @param User $user
     * @param string $pageName
     * @return GamificationSetting|null
     */
    public function getEggForPage(User $user, $pageName)
    {
        $eligibleEggs = $this->getEligibleEggs($user);

        return $eligibleEggs->where('page_name', $pageName)->first();
    }
}
