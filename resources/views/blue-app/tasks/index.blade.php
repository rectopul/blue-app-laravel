@extends('layouts.blueapp')

@section('content')
    <div class="pb-32">
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-pink-400">Minhas Metas</p>
                    <h1 class="text-[26px] font-bold tracking-tight text-slate-800">
                        Central de <span class="text-pink-500">Tasks</span>
                    </h1>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-[20px] border border-pink-100 bg-white shadow-sm">
                    <span class="material-symbols-outlined text-pink-500">task_alt</span>
                </div>
            </div>

            @if(count($plansStats) == 0)
                <div class="mt-8 rounded-[32px] border border-dashed border-slate-200 bg-white/50 p-8 text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-300">inventory_2</span>
                    <p class="mt-2 text-sm font-bold text-slate-500">Nenhum plano ativo</p>
                    <p class="mt-1 text-xs text-slate-400">Adquira um plano para começar a realizar tarefas.</p>
                </div>
            @else
                <div class="mt-4 flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    @foreach($plansStats as $index => $item)
                        <a href="#plan-{{ $item['purchase']->id }}" class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-white px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-600 shadow-sm ring-1 ring-black/5">
                            <span class="h-2 w-2 rounded-full bg-pink-500"></span>
                            {{ $item['stats']['package_name'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="px-5 mt-6 space-y-10">
            @foreach($plansStats as $item)
                <div id="plan-{{ $item['purchase']->id }}" class="scroll-mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">{{ $item['stats']['package_name'] }}</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Progresso: {{ $item['stats']['completed'] }}/{{ $item['stats']['limit'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">R$ {{ number_format($item['stats']['reward_per_task'], 2, ',', '.') }} / Task</p>
                        </div>
                    </div>

                    <div class="mb-6 h-2 w-full overflow-hidden rounded-full bg-slate-100 shadow-inner">
                        @php $percentage = $item['stats']['limit'] > 0 ? ($item['stats']['completed'] / $item['stats']['limit']) * 100 : 0; @endphp
                        <div class="h-full rounded-full bg-gradient-to-r from-pink-400 to-pink-500 transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                    </div>

                    <div class="space-y-4">
                        @forelse ($item['tasks'] as $task)
                            <div class="group flex items-center gap-4 rounded-[30px] border border-slate-100 bg-white p-5 shadow-sm transition-all hover:border-pink-100 hover:shadow-md">
                                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-pink-50 text-pink-500 transition-transform group-hover:scale-110">
                                    <span class="material-symbols-outlined text-2xl">{{ $task->icon ?: 'play_circle' }}</span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-sm font-bold text-slate-800">{{ $task->title ?: 'Assistir video' }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Tempo: {{ $task->watch_seconds ?? 30 }}s</p>
                                </div>

                                <a href="{{ route('user.tasks.show', ['id' => $task->id, 'purchase_id' => $item['purchase']->id]) }}" class="inline-flex h-9 items-center justify-center rounded-full bg-slate-800 px-5 text-[10px] font-black uppercase tracking-widest text-white shadow-lg transition-all active:scale-95 hover:bg-pink-500">
                                    Iniciar
                                </a>
                            </div>
                        @empty
                            @if ($item['stats']['remaining'] <= 0)
                                <div class="rounded-[30px] border border-emerald-100 bg-emerald-50/30 p-6 text-center">
                                    <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-500">
                                        <span class="material-symbols-outlined !text-lg">check_circle</span>
                                    </div>
                                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Plano concluído hoje!</p>
                                </div>
                            @else
                                <div class="rounded-[30px] border border-dashed border-slate-200 py-8 text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Nenhuma tarefa extra para este plano.</p>
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bottom Nav --}}
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
