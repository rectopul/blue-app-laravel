@extends('layouts.blueapp')

@section('content')
    <div class="pb-28">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-pink-400">Suas metas de hoje ✨</p>
                    <h1 class="text-[26px] font-bold tracking-tight text-slate-800">
                        Central de <span class="text-pink-500">Tarefas</span>
                    </h1>
                </div>
                <div class="h-12 w-12 rounded-[20px] bg-white flex items-center justify-center shadow-sm border border-pink-50">
                    <span class="text-xl">🎯</span>
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="mt-6 rounded-[32px] bg-white p-6 shadow-xl shadow-black/5 border border-pink-50 relative overflow-hidden">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Progresso Diário</p>
                        <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['completed'] }} / {{ $stats['limit'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-bold text-pink-400 uppercase tracking-wider">Ganho por tarefa</p>
                        <p class="mt-1 text-xl font-bold text-emerald-500">R$ {{ number_format($stats['reward_per_task'], 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-5 h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-pink-400 to-pink-500 rounded-full" style="width: {{ ($stats['completed'] / $stats['limit']) * 100 }}%"></div>
                </div>
                <p class="mt-3 text-[10px] font-medium text-slate-400 italic">Plano: {{ $stats['plan_name'] }}</p>
            </div>
        </div>

        {{-- Tasks List --}}
        <div class="px-5 mt-8">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Tarefas Disponíveis</h2>

            <div class="space-y-4">
                @forelse ($tasks as $task)
                    <div class="flex items-center gap-4 rounded-[30px] bg-white p-5 shadow-sm border border-slate-50 group hover:border-pink-200 transition-colors">
                        <div class="grid h-16 w-16 place-items-center rounded-2xl bg-pink-50 text-pink-500 group-hover:scale-110 transition-transform">
                            <span class="text-3xl">🎬</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-bold text-slate-800">{{ $task->title ?: 'Assistir Vídeo Recompensa' }}</h3>
                            <p class="text-[10px] font-medium text-slate-400 uppercase mt-1">Tempo estimado: 30s</p>
                        </div>

                        <a href="{{ route('user.tasks.show', $task->id) }}" class="h-10 px-4 rounded-full bg-[#F0F7FF] text-blue-500 text-xs font-bold flex items-center justify-center active:scale-95 transition-all">
                            Começar
                        </a>
                    </div>
                @empty
                    @if($stats['remaining'] <= 0)
                        <div class="py-12 text-center bg-white rounded-[40px] border border-dashed border-pink-200">
                            <span class="text-5xl block mb-4">🥳</span>
                            <h3 class="text-lg font-bold text-slate-800">Tudo pronto!</h3>
                            <p class="text-xs font-medium text-slate-400 px-8">Você concluiu todas as suas tarefas diárias. Volte amanhã para ganhar mais!</p>
                        </div>
                    @else
                        <div class="py-12 text-center bg-white rounded-[40px] border border-dashed border-slate-200">
                            <span class="text-5xl block mb-4">⌛</span>
                            <p class="text-xs font-medium text-slate-400">Novas tarefas sendo preparadas...</p>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        {{-- Back to Dashboard Navigation --}}
        <div class="fixed bottom-0 left-0 right-0 mx-auto max-w-[420px] bg-white/90 backdrop-blur-xl px-8 pb-10 pt-4 border-t border-pink-50 z-40 rounded-t-[40px] shadow-[0_-10px_40px_rgba(255,128,166,0.1)]">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 group">
                    <div class="h-12 w-12 rounded-2xl bg-white flex items-center justify-center text-slate-400 group-hover:text-pink-400 transition-colors">
                        <span class="material-symbols-outlined">home</span>
                    </div>
                </a>
                {{-- Outros ícones omitidos para foco --}}
            </div>
        </div>
    </div>
@endsection
