<?php

namespace App\Services;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Task;
use App\Models\UserTaskCompletion;
use Carbon\Carbon;

class TaskService
{
    public function getActivePlan(User $user)
    {
        $purchase = Purchase::with('package')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        return $purchase ? $purchase->package : null;
    }

    public function getAvailableTasks(User $user)
    {
        $plan = $this->getActivePlan($user);
        $limit = max(0, (int) ($plan ? $plan->daily_tasks_limit : 1));

        if ($limit === 0) {
            return collect();
        }

        $completedTodayIds = UserTaskCompletion::where('user_id', $user->id)
            ->whereDate('completion_date', Carbon::today())
            ->pluck('task_id')
            ->toArray();

        return Task::where('is_active', true)
            ->whereNotIn('id', $completedTodayIds)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

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

    public function calculateRewardPerTask($plan)
    {
        if (!$plan) {
            return 1.00;
        }

        if ($plan->daily_tasks_limit > 0) {
            return $plan->daily_reward / $plan->daily_tasks_limit;
        }

        return 0;
    }
}
