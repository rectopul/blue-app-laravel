<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\UserTaskCompletion;
use App\Models\UserLedger;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Central de Tarefas
     */
    public function index()
    {
        $user = auth()->user();
        $stats = $this->taskService->getDailyStats($user);
        $tasks = $this->taskService->getAvailableTasks($user);

        return view('blue-app.tasks.index', compact('stats', 'tasks'));
    }

    /**
     * Visualizar Vídeo da Tarefa
     */
    public function show($id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);

        // Basic check if already completed today
        $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereDate('completion_date', Carbon::today())
            ->exists();

        if ($alreadyCompleted) {
            return redirect()->route('user.tasks.index')->with('error', 'Tarefa já concluída hoje.');
        }

        return view('blue-app.tasks.show', compact('task'));
    }

    /**
     * Confirmar Conclusão da Tarefa
     */
    public function complete(Request $request, $id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);
        $today = Carbon::today();

        // 1. Validar last_task_completed_at (prevenção de cliques simultâneos)
        if ($user->last_task_completed_at && $user->last_task_completed_at->diffInSeconds(now()) < 30) {
            return response()->json(['message' => 'Aguarde um momento antes de confirmar outra tarefa.'], 429);
        }

        // 2. Validar limite diário
        $stats = $this->taskService->getDailyStats($user);
        if ($stats['completed'] >= $stats['limit']) {
            return response()->json(['message' => 'Limite de tarefas diárias atingido.'], 403);
        }

        // 3. Validar se já concluiu esta tarefa hoje
        $alreadyCompleted = UserTaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereDate('completion_date', $today)
            ->exists();

        if ($alreadyCompleted) {
            return response()->json(['message' => 'Você já concluiu esta tarefa hoje.'], 400);
        }

        // 4. Processar recompensa
        try {
            DB::beginTransaction();

            $reward = $stats['reward_per_task'];

            // Registrar conclusão
            UserTaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'reward_amount' => $reward,
                'completion_date' => $today,
            ]);

            // Atualizar saldo e timestamp
            $user->addBalance($reward);
            $user->last_task_completed_at = now();
            $user->save();

            // Ledger
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

            return response()->json([
                'message' => 'Tarefa concluída! R$ ' . number_format($reward, 2, ',', '.') . ' adicionados ao seu saldo.',
                'reward' => $reward,
                'new_balance' => $user->balance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao concluir tarefa: ' . $e->getMessage());
            return response()->json(['message' => 'Ocorreu um erro ao processar sua recompensa.'], 500);
        }
    }
}
