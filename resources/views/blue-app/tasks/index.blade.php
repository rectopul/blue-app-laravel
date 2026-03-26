@extends('layouts.blueapp')

@section('content')
    <div class="pb-28">
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-pink-400">Metas do dia</p>
                    <h1 class="text-[26px] font-bold tracking-tight text-slate-800">
                        Central de <span class="text-pink-500">Tasks</span>
                    </h1>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-[20px] border border-pink-100 bg-white shadow-sm">
                    <span class="material-symbols-outlined text-pink-500">task_alt</span>
                </div>
            </div>

            @php
                $percentage = $stats['limit'] > 0 ? ($stats['completed'] / $stats['limit']) * 100 : 0;
            @endphp

            <div class="mt-6 rounded-[32px] border border-pink-50 bg-white p-6 shadow-xl shadow-black/5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Progresso diario</p>
                        <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['completed'] }} / {{ $stats['limit'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-pink-400">Ganho por task</p>
                        <p class="mt-1 text-xl font-bold text-emerald-500">R$ {{ number_format($stats['reward_per_task'], 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-gradient-to-r from-pink-400 to-pink-500" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-2xl bg-slate-50 px-4 py-3">
                        <p class="font-bold uppercase tracking-wider text-slate-400">Plano</p>
                        <p class="mt-1 font-semibold text-slate-700">{{ $stats['plan_name'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-right">
                        <p class="font-bold uppercase tracking-wider text-slate-400">Restantes</p>
                        <p class="mt-1 font-semibold text-slate-700">{{ $stats['remaining'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-5 mt-8">
            <h2 class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Videos disponiveis</h2>

            <div class="space-y-4">
                @forelse ($tasks as $task)
                    <div class="group flex items-center gap-4 rounded-[30px] border border-slate-100 bg-white p-5 shadow-sm transition-colors hover:border-pink-200">
                        <div class="grid h-16 w-16 place-items-center rounded-2xl bg-pink-50 text-pink-500 transition-transform group-hover:scale-110">
                            <span class="material-symbols-outlined text-[30px]">{{ $task->icon ?: 'play_circle' }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-bold text-slate-800">{{ $task->title ?: 'Assistir video recompensa' }}</h3>
                            <p class="mt-1 text-[10px] font-medium uppercase text-slate-400">Tempo minimo: {{ $task->watch_seconds ?? 30 }}s</p>
                            @if($task->description)
                                <p class="mt-2 line-clamp-2 text-xs text-slate-500">{{ $task->description }}</p>
                            @endif
                        </div>

                        <a href="{{ route('user.tasks.show', $task->id) }}" class="inline-flex h-10 items-center justify-center rounded-full bg-[#F0F7FF] px-4 text-xs font-bold text-blue-500 transition-all active:scale-95">
                            Iniciar
                        </a>
                    </div>
                @empty
                    @if ($stats['remaining'] <= 0)
                        <div class="rounded-[40px] border border-dashed border-pink-200 bg-white py-12 text-center">
                            <span class="material-symbols-outlined text-5xl text-emerald-500">verified</span>
                            <h3 class="mt-4 text-lg font-bold text-slate-800">Tudo concluido</h3>
                            <p class="px-8 text-xs font-medium text-slate-400">Voce bateu a meta diaria e ja liberou o rendimento de hoje.</p>
                        </div>
                    @else
                        <div class="rounded-[40px] border border-dashed border-slate-200 bg-white py-12 text-center">
                            <span class="material-symbols-outlined text-5xl text-slate-300">hourglass_top</span>
                            <p class="mt-4 text-xs font-medium text-slate-400">Novas tasks estao sendo preparadas.</p>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 z-40 mx-auto max-w-[420px] rounded-t-[40px] border-t border-pink-50 bg-white/90 px-8 pb-10 pt-4 shadow-[0_-10px_40px_rgba(255,128,166,0.1)] backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 transition-colors group-hover:text-pink-400">
                        <span class="material-symbols-outlined">home</span>
                    </div>
                </a>
                <a href="{{ route('user.deposit') }}" class="flex flex-col items-center gap-1 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 transition-colors group-hover:text-pink-400">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                </a>
                <a href="{{ route('user.withdraw') }}" class="flex flex-col items-center gap-1 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 transition-colors group-hover:text-pink-400">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                    </div>
                </a>
                <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 transition-colors group-hover:text-pink-400">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
