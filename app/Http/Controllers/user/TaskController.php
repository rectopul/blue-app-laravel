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

        return view('blue-app.tasks.index', compact('plansStats'));
    }

    public function show($id, Request $request)
    {
        $purchaseId = $request->query('purchase_id');
        if (!$purchaseId) {
            return redirect()->route('user.tasks.index')->with('error', 'Selecione um plano para realizar a tarefa.');
        }

        $user = auth()->user();
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
        $task = Task::findOrFail($id);
        $purchaseId = $request->input('purchase_id');

        if (!$purchaseId) {
            return response()->json(['message' => 'Plano não identificado.'], 400);
        }

        $purchase = Purchase::where('id', $purchaseId)->where('user_id', $user->id)->firstOrFail();
        $today = Carbon::today();
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
