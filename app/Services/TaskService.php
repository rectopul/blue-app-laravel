<?php

namespace App\Services;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Task;
use App\Models\UserTaskCompletion;
use Carbon\Carbon;

class TaskService
{
    public function getActivePurchases(User $user)
    {
        return Purchase::with('package')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->get();
    }

    public function getDailyStatsForPurchase(User $user, Purchase $purchase)
    {
        $package = $purchase->package;
        $limit = $package ? $package->daily_tasks_limit : 0;

        $count = UserTaskCompletion::where('user_id', $user->id)
            ->where('purchase_id', $purchase->id)
            ->whereDate('completion_date', Carbon::today())
            ->count();

        return [
            'purchase_id' => $purchase->id,
            'completed' => $count,
            'limit' => $limit,
            'remaining' => max(0, $limit - $count),
            'package_name' => $package ? $package->name : 'N/A',
            'reward_per_task' => $this->calculateRewardPerTaskForPurchase($purchase)
        ];
    }

    public function calculateRewardPerTaskForPurchase(Purchase $purchase)
    {
        $package = $purchase->package;
        if (!$package || $package->daily_tasks_limit <= 0) {
            return 0;
        }

        // De acordo com a nova UI do Admin, 'daily_reward' é a recompensa individual por cada tarefa.
        if ($package->daily_reward > 0) {
            return (float) $package->daily_reward;
        }

        // Fallback: se não houver daily_reward definido, divide o daily_income total (ROI diário) pelas tasks.
        return $purchase->daily_income / $package->daily_tasks_limit;
    }

    public function getAvailableTasksForPurchase(User $user, Purchase $purchase)
    {
        $stats = $this->getDailyStatsForPurchase($user, $purchase);

        if ($stats['remaining'] <= 0) {
            return collect();
        }

        $completedTodayForPurchaseIds = UserTaskCompletion::where('user_id', $user->id)
            ->where('purchase_id', $purchase->id)
            ->whereDate('completion_date', Carbon::today())
            ->pluck('task_id')
            ->toArray();

        return Task::where('is_active', true)
            ->whereNotIn('id', $completedTodayForPurchaseIds)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit($stats['remaining'])
            ->get();
    }

    // Mantendo métodos legados para compatibilidade se necessário, mas adaptando
    public function getDailyStats(User $user)
    {
        $purchases = $this->getActivePurchases($user);
        $allStats = [];

        foreach ($purchases as $purchase) {
            $allStats[] = $this->getDailyStatsForPurchase($user, $purchase);
        }

        return $allStats;
    }
}
