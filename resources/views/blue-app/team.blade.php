@extends('layouts.blueapp')

@section('content')
    <div x-data="{ level: 1 }" class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[34px] bg-gradient-to-b from-[#CFE7FF] to-[#EEF4F9] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                </a>
                <h1 class="text-lg font-bold text-slate-900 uppercase tracking-widest">Minha Equipe</h1>
                <div class="w-11"></div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="bg-white/60 backdrop-blur-sm p-4 rounded-3xl border border-white/40">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Membros Totais</p>
                    <p class="text-xl font-black text-slate-900">{{ number_format($team_size) }}</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm p-4 rounded-3xl border border-white/40">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Comissões</p>
                    <p class="text-xl font-black text-[#2C95EF]">R$ {{ number_format($levelTotalCommission1 + $levelTotalCommission2 + $levelTotalCommission3, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-4 bg-slate-900 p-5 rounded-[28px] shadow-xl shadow-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Volume de Investimento</p>
                        <p class="text-2xl font-black text-white">R$ {{ number_format($totalInvestment, 2, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-400">trending_up</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-white/10 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full bg-[#2C95EF] w-3/4"></div>
                    </div>
                    <span class="text-[10px] font-bold text-white/60">Top Afiliado</span>
                </div>
            </div>
        </div>

        {{-- Level Tabs --}}
        <div class="px-5 -mt-6">
            <div class="bg-white rounded-[28px] p-2 shadow-lg shadow-slate-200/50 flex gap-1">
                <button @click="level = 1" :class="level === 1 ? 'bg-[#2C95EF] text-white shadow-md' : 'text-slate-400'" class="flex-1 py-3 text-xs font-bold rounded-[22px] transition-all smooth-transition">
                    Nível 1 ({{ $first_level_users->count() }})
                </button>
                <button @click="level = 2" :class="level === 2 ? 'bg-[#2C95EF] text-white shadow-md' : 'text-slate-400'" class="flex-1 py-3 text-xs font-bold rounded-[22px] transition-all smooth-transition">
                    Nível 2 ({{ $second_level_users->count() }})
                </button>
                <button @click="level = 3" :class="level === 3 ? 'bg-[#2C95EF] text-white shadow-md' : 'text-slate-400'" class="flex-1 py-3 text-xs font-bold rounded-[22px] transition-all smooth-transition">
                    Nível 3 ({{ $third_level_users->count() }})
                </button>
            </div>
        </div>

        {{-- User List --}}
        <div class="px-5 mt-6 space-y-3">
            {{-- Level 1 --}}
            <template x-if="level === 1">
                <div class="space-y-3">
                    @forelse($first_level_users as $u)
                        @include('blue-app.partials.user-card', ['u' => $u, 'lv' => 1])
                    @empty
                        @include('blue-app.partials.empty-team')
                    @endforelse
                </div>
            </template>

            {{-- Level 2 --}}
            <template x-if="level === 2">
                <div class="space-y-3">
                    @forelse($second_level_users as $u)
                        @include('blue-app.partials.user-card', ['u' => $u, 'lv' => 2])
                    @empty
                        @include('blue-app.partials.empty-team')
                    @endforelse
                </div>
            </template>

            {{-- Level 3 --}}
            <template x-if="level === 3">
                <div class="space-y-3">
                    @forelse($third_level_users as $u)
                        @include('blue-app.partials.user-card', ['u' => $u, 'lv' => 3])
                    @empty
                        @include('blue-app.partials.empty-team')
                    @endforelse
                </div>
            </template>
        </div>

        {{-- Share Section --}}
        <div class="px-5 mt-10">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[32px] p-6 text-white relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-blue-500/10 rounded-full"></div>

                <h3 class="text-lg font-bold mb-4">Convide Amigos</h3>
                <p class="text-xs text-white/60 mb-6 leading-relaxed">Ganhe comissões em 3 níveis sobre todos os investimentos da sua rede.</p>

                <div class="flex items-center gap-3 bg-white/10 p-4 rounded-2xl border border-white/10 mb-4">
                    <div class="flex-1 overflow-hidden">
                        <p class="text-[10px] text-white/40 uppercase font-bold mb-1">Seu Link de Convite</p>
                        <p class="text-xs font-mono truncate">{{ refferUrl($user) }}</p>
                    </div>
                    <button onclick="copyToClipboard('{{ refferUrl($user) }}')" class="bg-white text-slate-900 px-4 py-2 rounded-xl text-[10px] font-bold active:scale-95 transition-all">
                        COPIAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copiado com sucesso!');
            });
        }
    </script>
    @endpush
@endsection
