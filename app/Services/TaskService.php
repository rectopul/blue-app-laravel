<?php

namespace App\Services;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Task;
use App\Models\UserTaskCompletion;
use Carbon\Carbon;

class TaskService
{
    /**
     * Get the user's active plan (Package).
     */
    public function getActivePlan(User $user)
    {
        // Get the latest active purchase
        $purchase = Purchase::with('package')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        return $purchase ? $purchase->package : null;
    }

    /**
     * Get tasks available for the user today.
     */
    public function getAvailableTasks(User $user)
    {
        $plan = $this->getActivePlan($user);
        $limit = $plan ? $plan->daily_tasks_limit : 1; // Default 1 for free plan? (User requested 1 for free)

        // Get tasks not completed today
        $completedTodayIds = UserTaskCompletion::where('user_id', $user->id)
            ->whereDate('completion_date', Carbon::today())
            ->pluck('task_id')
            ->toArray();

        $tasks = Task::where('is_active', true)
            ->whereNotIn('id', $completedTodayIds)
            ->limit($limit)
            ->get();

        return $tasks;
    }

    /**
     * Get completion stats for today.
     */
    public function getDailyStats(User $user)
    {
        $plan = $this->getActivePlan($user);
        $limit = $plan ? $plan->daily_tasks_limit : 1;
        $count = UserTaskCompletion::where('user_id', $user->id)
            ->whereDate('completion_date', Carbon::today())
            ->count();

        return [
            'completed' => $count,
            'limit' => $limit,
            'remaining' => max(0, $limit - $count),
            'plan_name' => $plan ? $plan->name : 'Plano Gratuito',
            'reward_per_task' => $this->calculateRewardPerTask($plan)
        ];
    }

    /**
     * Calculate reward per task based on plan.
     */
    public function calculateRewardPerTask($plan)
    {
        if (!$plan) {
            return 1.00; // Plano Gratuito
        }

        if ($plan->daily_tasks_limit > 0) {
            return $plan->daily_reward / $plan->daily_tasks_limit;
        }

        return 0;
    }
}
