@extends('layouts.blueapp')

@section('content')
    <div x-data="taskViewer()" class="min-h-screen bg-[#F0F7FF] flex flex-col">
        {{-- Header --}}
        <div class="px-5 pt-8 pb-4 flex items-center justify-between">
            <a href="{{ route('user.tasks.index') }}" class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-400">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Executando Tarefa</h1>
            <div class="w-10"></div>
        </div>

        {{-- Video Container --}}
        <div class="px-5 mt-4 flex-1 flex flex-col">
            <div class="aspect-video w-full rounded-[30px] bg-black overflow-hidden shadow-2xl relative">
                @if($task->video_url)
                    <iframe class="w-full h-full" src="{{ $task->video_url }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center text-white/50 flex-col gap-2">
                        <span class="text-4xl">📽️</span>
                        <span class="text-xs">O vídeo aparecerá aqui</span>
                    </div>
                @endif

                {{-- Timer Overlay --}}
                <div x-show="timeLeft > 0" class="absolute inset-0 bg-black/60 backdrop-blur-sm flex flex-col items-center justify-center text-white transition-opacity duration-500">
                    <div class="relative h-24 w-24 flex items-center justify-center">
                        <svg class="h-full w-full rotate-[-90deg]">
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="4" fill="transparent" class="text-white/20" />
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="4" fill="transparent" :style="`stroke-dasharray: 251.3; stroke-dashoffset: ${251.3 - (251.3 * (30 - timeLeft) / 30)}`" class="text-pink-500 transition-all duration-1000 ease-linear" />
                        </svg>
                        <span class="absolute text-2xl font-black" x-text="timeLeft"></span>
                    </div>
                    <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em]">Aguarde a conclusão</p>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="mt-8 text-center px-4">
                <h2 class="text-xl font-bold text-slate-800">{{ $task->title ?: 'Assistir vídeo para ganhar' }}</h2>
                <p class="mt-3 text-sm text-slate-400 font-medium leading-relaxed">
                    Assista o vídeo até o final do cronômetro para que o bônus seja creditado em sua carteira. Não feche esta aba.
                </p>
            </div>

            {{-- Confirmation Button --}}
            <div class="mt-auto mb-12 px-4">
                <button
                    @click="completeTask()"
                    :disabled="timeLeft > 0 || loading"
                    :class="timeLeft > 0 ? 'bg-slate-200 text-slate-400' : 'bg-emerald-500 text-white shadow-lg shadow-emerald-100'"
                    class="w-full py-4 rounded-[24px] text-sm font-bold transition-all active:scale-[0.98] flex items-center justify-center gap-3"
                >
                    <template x-if="loading">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <span x-text="timeLeft > 0 ? 'Assista o vídeo...' : 'Confirmar e Coletar Bônus'"></span>
                    <span x-show="timeLeft === 0 && !loading" class="text-xl">💰</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function taskViewer() {
            return {
                timeLeft: 30,
                loading: false,
                init() {
                    let timer = setInterval(() => {
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
                        const response = await axios.post("{{ route('user.tasks.complete', $task->id) }}");
                        if (window.showToast) {
                            window.showToast(response.data.message, 'success');
                        } else {
                            alert(response.data.message);
                        }
                        setTimeout(() => {
                            window.location.href = "{{ route('user.tasks.index') }}";
                        }, 2000);
                    } catch (error) {
                        const message = error.response?.data?.message || 'Erro ao confirmar tarefa.';
                        if (window.showToast) {
                            window.showToast(message, 'error');
                        } else {
                            alert(message);
                        }
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
