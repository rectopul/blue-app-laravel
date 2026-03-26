@extends('layouts.blueapp')

@section('content')
    <div x-data="{ level: 1 }" class="pb-32">
        {{-- Header --}}
        <div
            class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10 mb-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}"
                    class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Equipe de <span class="text-pink-500">Coleta</span></h1>
                <div class="w-12"></div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-[30px] shadow-xl shadow-black/5 border border-pink-50">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Membros</p>
                    <p class="text-2xl font-black text-slate-800">{{ number_format($team_size) }}</p>
                </div>
                <div class="bg-white p-5 rounded-[30px] shadow-xl shadow-black/5 border border-blue-50">
                    <p class="text-[11px] font-bold text-pink-400 uppercase tracking-wider mb-1">Bônus</p>
                    <p class="text-2xl font-black text-emerald-500">R$
                        {{ number_format($levelTotalCommission1 + $levelTotalCommission2 + $levelTotalCommission3, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="mt-4 bg-gradient-to-br from-[#FFADCC] to-[#FF80A6] p-6 rounded-[35px] shadow-xl shadow-pink-200 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-white/80 uppercase tracking-widest">Volume da Rede</p>
                        <p class="text-2xl font-black text-white">R$ {{ number_format($totalInvestment, 2, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center border border-white/30">
                        <span class="text-2xl">📈</span>
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
            <div class="bg-white rounded-[30px] p-2 shadow-xl shadow-black/5 flex gap-1 border border-pink-50">
                <button @click="level = 1" :class="level === 1 ? 'bg-pink-500 text-white shadow-lg shadow-pink-200' : 'text-slate-400'"
                    class="flex-1 py-3.5 text-[11px] font-bold rounded-[24px] transition-all smooth-transition uppercase tracking-wider">
                    Nível 1
                </button>
                <button @click="level = 2" :class="level === 2 ? 'bg-pink-500 text-white shadow-lg shadow-pink-200' : 'text-slate-400'"
                    class="flex-1 py-3.5 text-[11px] font-bold rounded-[24px] transition-all smooth-transition uppercase tracking-wider">
                    Nível 2
                </button>
                <button @click="level = 3" :class="level === 3 ? 'bg-pink-500 text-white shadow-lg shadow-pink-200' : 'text-slate-400'"
                    class="flex-1 py-3.5 text-[11px] font-bold rounded-[24px] transition-all smooth-transition uppercase tracking-wider">
                    Nível 3
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
            <div
                class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[32px] p-6 text-white relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-blue-500/10 rounded-full"></div>

                <h3 class="text-lg font-bold mb-4">Convide Amigos</h3>
                <p class="text-xs text-white/60 mb-6 leading-relaxed">Ganhe comissões em 3 níveis sobre todos os
                    investimentos da sua rede.</p>

                <div class="flex items-center gap-3 bg-white/10 p-4 rounded-2xl border border-white/10 mb-4">
                    <div class="flex-1 overflow-hidden">
                        <p class="text-[10px] text-white/40 uppercase font-bold mb-1">Seu Link de Convite</p>
                        <p class="text-xs font-mono truncate">{{ refferUrl($user) }}</p>
                    </div>
                    <button onclick="copyToClipboard('{{ refferUrl($user) }}')"
                        class="bg-white text-slate-900 px-4 py-2 rounded-xl text-[10px] font-bold active:scale-95 transition-all">
                        COPIAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
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
