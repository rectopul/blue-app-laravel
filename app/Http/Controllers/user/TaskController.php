<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Task;
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
        $stats = $this->taskService->getDailyStats($user);
        $tasks = $this->taskService->getAvailableTasks($user);

        return view('blue-app.tasks.index', compact('stats', 'tasks'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);

        $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereDate('completion_date', Carbon::today())
            ->exists();

        if ($alreadyCompleted) {
            return redirect()->route('user.tasks.index')->with('error', 'Tarefa ja concluida hoje.');
        }

        session([
            $this->getTaskSessionKey($task->id) => [
                'started_at' => now()->timestamp,
                'watch_seconds' => (int) ($task->watch_seconds ?: 30),
            ],
        ]);

        return view('blue-app.tasks.show', compact('task'));
    }

    public function complete(Request $request, $id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);
        $today = Carbon::today();
        $sessionData = session($this->getTaskSessionKey($task->id));

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

        if ($user->last_task_completed_at && $user->last_task_completed_at->diffInSeconds(now()) < 30) {
            return response()->json(['message' => 'Aguarde um momento antes de confirmar outra tarefa.'], 429);
        }

        $stats = $this->taskService->getDailyStats($user);
        if ($stats['completed'] >= $stats['limit']) {
            return response()->json(['message' => 'Limite diario de tarefas atingido.'], 403);
        }

        $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereDate('completion_date', $today)
            ->exists();

        if ($alreadyCompleted) {
            return response()->json(['message' => 'Voce ja concluiu esta tarefa hoje.'], 400);
        }

        try {
            DB::beginTransaction();

            $reward = $stats['reward_per_task'];

            UserTaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'reward_amount' => $reward,
                'completion_date' => $today,
            ]);

            $user->addBalance((float) $reward);
            $user->last_task_completed_at = now();
            $user->save();

            UserLedger::create([
                'user_id' => $user->id,
                'reason' => 'task_reward',
                'perticulation' => "Recompensa por tarefa: {$task->title}",
                'amount' => $reward,
                'credit' => $reward,
                'status' => 'approved',
                'date' => now()->format('d-m-Y H:i')
            ]);

            DB::commit();
            session()->forget($this->getTaskSessionKey($task->id));

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

    private function getTaskSessionKey(int $taskId): string
    {
        return 'task_watch.' . auth()->id() . '.' . $taskId;
    }
}

