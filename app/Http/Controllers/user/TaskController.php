<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Purchase;
use App\Models\UserLedger;
use App\Models\UserTaskCompletion;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $user = auth()->user();
        $purchases = $this->taskService->getActivePurchases($user);

        $plansStats = [];
        foreach ($purchases as $purchase) {
            $plansStats[] = [
                'purchase' => $purchase,
                'stats' => $this->taskService->getDailyStatsForPurchase($user, $purchase),
                'tasks' => $this->taskService->getAvailableTasksForPurchase($user, $purchase)
            ];
        }

        // Add Free Task
        $setting = \App\Models\Setting::find(1);
        $freeTask = null;
        if ($setting && $setting->free_task_video_url) {
            $completedFreeToday = UserTaskCompletion::where('user_id', $user->id)
                ->whereNull('purchase_id')
                ->whereNull('task_id')
                ->whereDate('completion_date', Carbon::today())
                ->exists();

            $freeTask = [
                'video_url' => $setting->free_task_video_url,
                'reward' => $setting->free_task_reward,
                'seconds' => $setting->free_task_seconds,
                'completed' => $completedFreeToday
            ];
        }

        return view('blue-app.tasks.index', compact('plansStats', 'freeTask'));
    }

    public function show($id, Request $request)
    {
        $user = auth()->user();
        $purchaseId = $request->query('purchase_id');

        if ($id === 'free') {
            $setting = \App\Models\Setting::find(1);
            if (!$setting || !$setting->free_task_video_url) {
                return redirect()->route('user.tasks.index')->with('error', 'Tarefa grátis não configurada.');
            }

            $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
                ->whereNull('purchase_id')
                ->whereNull('task_id')
                ->whereDate('completion_date', Carbon::today())
                ->exists();

            if ($alreadyCompleted) {
                return redirect()->route('user.tasks.index')->with('error', 'Tarefa grátis já concluída hoje.');
            }

            $task = new Task([
                'id' => 0,
                'title' => 'Tarefa Grátis Diária',
                'description' => 'Assista ao vídeo para coletar seu bônus diário gratuito.',
                'video_url' => $setting->free_task_video_url,
                'watch_seconds' => $setting->free_task_seconds,
                'amount' => $setting->free_task_reward,
            ]);
            $task->id = 0; // Ensure it's 0 or something identifiable

            $purchase = null;

            session([
                'task_watch.' . $user->id . '.free' => [
                    'started_at' => now()->timestamp,
                    'watch_seconds' => (int) ($task->watch_seconds ?: 30),
                ],
            ]);

            return view('blue-app.tasks.show', compact('task', 'purchase'));
        }

        if (!$purchaseId) {
            return redirect()->route('user.tasks.index')->with('error', 'Selecione um plano para realizar a tarefa.');
        }

        $task = Task::findOrFail($id);
        $purchase = Purchase::where('id', $purchaseId)->where('user_id', $user->id)->firstOrFail();

        $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('purchase_id', $purchase->id)
            ->whereDate('completion_date', Carbon::today())
            ->exists();

        if ($alreadyCompleted) {
            return redirect()->route('user.tasks.index')->with('error', 'Tarefa ja concluida para este plano hoje.');
        }

        session([
            $this->getTaskSessionKey($task->id, $purchase->id) => [
                'started_at' => now()->timestamp,
                'watch_seconds' => (int) ($task->watch_seconds ?: 30),
            ],
        ]);

        return view('blue-app.tasks.show', compact('task', 'purchase'));
    }

    public function complete(Request $request, $id)
    {
        $user = auth()->user();
        $purchaseId = $request->input('purchase_id');
        $today = Carbon::today();

        if ($id === '0' || $id === 'free') {
            $setting = \App\Models\Setting::find(1);
            if (!$setting || !$setting->free_task_video_url) {
                return response()->json(['message' => 'Tarefa grátis não configurada.'], 400);
            }

            $sessionKey = 'task_watch.' . $user->id . '.free';
            $sessionData = session($sessionKey);

            if (!$sessionData || empty($sessionData['started_at'])) {
                return response()->json(['message' => 'Sessão inválida.'], 422);
            }

            $requiredWatchSeconds = max(5, (int) ($setting->free_task_seconds ?: 30));
            $elapsed = now()->timestamp - (int) $sessionData['started_at'];

            if ($elapsed < $requiredWatchSeconds) {
                return response()->json(['message' => 'Aguarde o tempo mínimo.'], 422);
            }

            $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
                ->whereNull('purchase_id')
                ->whereNull('task_id')
                ->whereDate('completion_date', $today)
                ->exists();

            if ($alreadyCompleted) {
                return response()->json(['message' => 'Tarefa grátis já concluída hoje.'], 400);
            }

            try {
                DB::beginTransaction();

                $reward = (float) $setting->free_task_reward;

                UserTaskCompletion::create([
                    'user_id' => $user->id,
                    'task_id' => null,
                    'purchase_id' => null,
                    'reward_amount' => $reward,
                    'completion_date' => $today,
                ]);

                $user->addBalance($reward);
                $user->last_task_completed_at = now();
                $user->save();

                UserLedger::create([
                    'user_id' => $user->id,
                    'reason' => 'daily_task_bonus',
                    'perticulation' => "Bônus de tarefa diária",
                    'amount' => $reward,
                    'credit' => $reward,
                    'status' => 'approved',
                    'date' => now()->format('d-m-Y H:i')
                ]);

                DB::commit();
                session()->forget($sessionKey);

                return response()->json([
                    'message' => 'Tarefa grátis concluída! R$ ' . number_format($reward, 2, ',', '.') . ' adicionados ao seu saldo.',
                    'reward' => $reward,
                    'new_balance' => $user->fresh()->balance
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao concluir tarefa grátis: ' . $e->getMessage());
                return response()->json(['message' => 'Ocorreu um erro ao processar sua recompensa.'], 500);
            }
        }

        $task = Task::findOrFail($id);

        if (!$purchaseId) {
            return response()->json(['message' => 'Plano não identificado.'], 400);
        }

        $purchase = Purchase::where('id', $purchaseId)->where('user_id', $user->id)->firstOrFail();
        $sessionData = session($this->getTaskSessionKey($task->id, $purchase->id));

        if (!$sessionData || empty($sessionData['started_at'])) {
            return response()->json([
                'message' => 'Abra a tarefa e aguarde o tempo minimo antes de confirmar.',
            ], 422);
        }

        $requiredWatchSeconds = max(5, (int) ($task->watch_seconds ?: 30));
        $elapsed = now()->timestamp - (int) $sessionData['started_at'];

        if ($elapsed < $requiredWatchSeconds) {
            return response()->json([
                'message' => 'Aguarde ' . ($requiredWatchSeconds - $elapsed) . 's para concluir a tarefa.',
                'remaining_seconds' => $requiredWatchSeconds - $elapsed,
            ], 422);
        }

        if ($user->last_task_completed_at && $user->last_task_completed_at->diffInSeconds(now()) < 5) {
            return response()->json(['message' => 'Aguarde um momento antes de confirmar outra tarefa.'], 429);
        }

        $stats = $this->taskService->getDailyStatsForPurchase($user, $purchase);
        if ($stats['completed'] >= $stats['limit']) {
            return response()->json(['message' => 'Limite diario de tarefas atingido para este plano.'], 403);
        }

        $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('purchase_id', $purchase->id)
            ->whereDate('completion_date', $today)
            ->exists();

        if ($alreadyCompleted) {
            return response()->json(['message' => 'Voce ja concluiu esta tarefa hoje para este plano.'], 400);
        }

        try {
            DB::beginTransaction();

            $reward = $this->taskService->calculateRewardPerTaskForPurchase($purchase);

            UserTaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'purchase_id' => $purchase->id,
                'reward_amount' => $reward,
                'completion_date' => $today,
            ]);

            $user->addBalance((float) $reward);
            $user->last_task_completed_at = now();
            $user->save();

            UserLedger::create([
                'user_id' => $user->id,
                'reason' => 'task_reward',
                'perticulation' => "Recompensa task ({$purchase->package->name}): {$task->title}",
                'amount' => $reward,
                'credit' => $reward,
                'status' => 'approved',
                'date' => now()->format('d-m-Y H:i')
            ]);

            DB::commit();
            session()->forget($this->getTaskSessionKey($task->id, $purchase->id));

            return response()->json([
                'message' => 'Tarefa concluida! R$ ' . number_format($reward, 2, ',', '.') . ' adicionados ao seu saldo.',
                'reward' => $reward,
                'new_balance' => $user->fresh()->balance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao concluir tarefa: ' . $e->getMessage());
            return response()->json(['message' => 'Ocorreu um erro ao processar sua recompensa.'], 500);
        }
    }

    private function getTaskSessionKey(int $taskId, int $purchaseId): string
    {
        return 'task_watch.' . auth()->id() . '.' . $taskId . '.' . $purchaseId;
    }
}
