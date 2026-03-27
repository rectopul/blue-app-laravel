@extends('layouts.blueapp')

@section('content')
    <div x-data="taskViewer()" class="min-h-screen bg-[#F0F7FF] flex flex-col pb-12">
        <div class="px-5 pt-8 pb-4 flex items-center justify-between">
            <a href="{{ route('user.tasks.index') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm text-slate-400">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-sm font-bold uppercase tracking-widest text-slate-800">Executando task</h1>
            <div class="w-10"></div>
        </div>

        <div class="px-5 mt-4 flex-1 flex flex-col">
            <div class="aspect-video w-full overflow-hidden rounded-[30px] bg-black shadow-2xl relative">
                @if($task->video_url)
                    <iframe class="h-full w-full" src="{{ $task->video_url }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                @else
                    <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-white/50">
                        <span class="material-symbols-outlined text-5xl">movie</span>
                        <span class="text-xs">O video aparecera aqui</span>
                    </div>
                @endif

                <div x-show="timeLeft > 0" class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 text-white backdrop-blur-sm transition-opacity duration-500">
                    <div class="relative flex h-24 w-24 items-center justify-center">
                        <svg class="h-full w-full rotate-[-90deg]">
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="4" fill="transparent" class="text-white/20" />
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="4" fill="transparent" :style="`stroke-dasharray: 251.3; stroke-dashoffset: ${251.3 - (251.3 * (totalSeconds - timeLeft) / totalSeconds)}`" class="text-pink-500 transition-all duration-1000 ease-linear" />
                        </svg>
                        <span class="absolute text-2xl font-black" x-text="timeLeft"></span>
                    </div>
                    <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em]">Aguarde a conclusao</p>
                </div>
            </div>

            <div class="mt-8 px-4 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-[24px] bg-pink-50 text-pink-500">
                    <span class="material-symbols-outlined text-[30px]">{{ $task->icon ?: 'play_circle' }}</span>
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ $task->title ?: 'Assistir video para ganhar' }}</h2>
                <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-[10px] font-bold text-blue-500 uppercase">
                    <span class="material-symbols-outlined !text-xs">inventory_2</span>
                    Plano: {{ $purchase->package->name }}
                </div>
                <p class="mt-3 text-sm font-medium leading-relaxed text-slate-400">
                    {{ $task->description ?: 'Assista ao video ate o fim do contador para liberar o credito do rendimento diario deste plano.' }}
                </p>
            </div>

            <div class="mt-6 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold uppercase tracking-wider text-slate-400">Tempo minimo</span>
                    <span class="font-bold text-slate-700">{{ $task->watch_seconds ?? 30 }}s</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs">
                    <span class="font-bold uppercase tracking-wider text-slate-400">Status</span>
                    <span :class="timeLeft > 0 ? 'text-amber-500' : 'text-emerald-500'" class="font-bold" x-text="timeLeft > 0 ? 'Em andamento' : 'Pronto para confirmar'"></span>
                </div>
            </div>

            <div class="mt-12 mb-12 px-4">
                <button
                    @click="completeTask()"
                    :disabled="timeLeft > 0 || loading"
                    :class="timeLeft > 0 ? 'bg-slate-200 text-slate-400' : 'bg-emerald-500 text-white shadow-lg shadow-emerald-100'"
                    class="flex w-full items-center justify-center gap-3 rounded-[24px] py-4 text-sm font-bold transition-all active:scale-[0.98]"
                >
                    <template x-if="loading">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <span x-text="timeLeft > 0 ? 'Assista o video...' : 'Confirmar e coletar bonus'"></span>
                    <span x-show="timeLeft === 0 && !loading" class="material-symbols-outlined">rewarded_ads</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function taskViewer() {
            return {
                totalSeconds: {{ (int) ($task->watch_seconds ?? 30) }},
                timeLeft: {{ (int) ($task->watch_seconds ?? 30) }},
                loading: false,
                init() {
                    const timer = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            clearInterval(timer);
                        }
                    }, 1000);
                },
                async completeTask() {
                    if (this.timeLeft > 0 || this.loading) return;

                    this.loading = true;
                    try {
                        const response = await fetch("{{ route('user.tasks.complete', $task->id) }}", {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                purchase_id: {{ $purchase->id }}
                            })
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            throw new Error(result.message || 'Erro ao confirmar tarefa.');
                        }

                        alert(result.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('user.tasks.index') }}";
                        }, 1200);
                    } catch (error) {
                        alert(error.message || 'Erro ao confirmar tarefa.');
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
