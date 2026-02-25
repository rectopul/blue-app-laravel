@extends('layouts.dmk')

@section('title', 'Minha Rede')

@section('content')
    <header class="relative w-full h-[180px] bg-cover bg-top"
        style="background-image: url('{{ asset(main_root() . '/assets/img/team.jpg') }}');">
        <div class="absolute inset-0 bg-gradient-to-t from-mirage-950 via-mirage-950/60 to-transparent"></div>
        <div class="absolute bottom-6 left-0 w-full px-6">
            <h1 class="text-white text-2xl font-black tracking-tight">Minha Equipe</h1>
            <p class="text-emerald-400 text-xs font-bold uppercase tracking-widest">Gestão de Afiliados</p>
        </div>
    </header>

    <div class="min-h-screen bg-mirage-950 pb-24">
        <div class="max-w-7xl mx-auto px-4 -mt-4 relative z-10">

            <div class="bg-white rounded-[24px] shadow-2xl p-5 mb-6 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Seu código
                            exclusivo</span>
                        <span class="text-lg font-black text-slate-900 font-mono">{{ $user->ref_id }}</span>
                    </div>
                    <button onclick="shareOrCopy('{{ $user->ref_id }}')"
                        class="bg-slate-100 text-slate-600 p-3 rounded-xl active:scale-90 transition-all">
                        <span class="material-symbols-outlined !text-xl">content_copy</span>
                    </button>
                </div>

                <button onclick="shareOrCopy('{{ refferUrl(user()) }}')"
                    class="w-full bg-slate-900 hover:bg-elm-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-slate-200 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">share</span>
                    INDICAR AGORA
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-mirage-900/50 border border-white/5 p-4 rounded-2xl">
                    <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center mb-2">
                        <span class="material-symbols-outlined text-emerald-400 !text-lg">groups</span>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Equipe Total</p>
                    <p class="text-xl font-black text-elm-950">{{ number_format($team_size) }}</p>
                </div>

                <div class="bg-mirage-900/50 border border-white/5 p-4 rounded-2xl">
                    <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center mb-2">
                        <span class="material-symbols-outlined text-amber-400 !text-lg">payments</span>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Comissões</p>
                    <p class="text-xl font-black text-elm-950">R$
                        {{ number_format($levelTotalCommission1 + $levelTotalCommission2 + $levelTotalCommission3, 2, ',', '.') }}
                    </p>
                </div>

                <div
                    class="bg-mirage-900/50 border border-white/5 p-4 rounded-2xl col-span-2 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Volume de Investimentos</p>
                        <p class="text-2xl font-black text-elm-950">R$ {{ number_format($totalInvestment, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-400">trending_up</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="text-elm-950 font-black text-sm uppercase tracking-widest">Membros da Rede</h3>
                <span
                    class="bg-emerald-500 text-[10px] font-black px-2 py-1 rounded-md text-emerald-950 uppercase">Ativos</span>
            </div>

            <div class="space-y-3">
                @php
                    $all_users = $first_level_users
                        ->map(fn($u) => ['data' => $u, 'lv' => 1])
                        ->concat($second_level_users->map(fn($u) => ['data' => $u, 'lv' => 2]))
                        ->concat($third_level_users->map(fn($u) => ['data' => $u, 'lv' => 3]));
                @endphp

                @forelse ($all_users as $item)
                    @php $u = $item['data']; @endphp
                    <div
                        class="bg-white rounded-[20px] p-4 flex items-center justify-between shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <div>
                                <p class="text-slate-900 font-black text-sm font-mono tracking-tighter">
                                    {{ formatPhone($u->phone) }}</p>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-900 text-white uppercase">Nível
                                        {{ $item['lv'] }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Dep: R$
                                        {{ number_format($u->deposits_sum_amount, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-emerald-600 !text-sm">verified</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <span class="material-symbols-outlined text-slate-600 !text-5xl mb-2">supervised_user_circle</span>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Nenhum indicado encontrado</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        async function shareOrCopy(text) {
            const isUrl = text.includes('http');

            if (navigator.share && /Mobi|Android|iPhone/i.test(navigator.userAgent)) {
                try {
                    await navigator.share({
                        title: 'Convite Especial',
                        text: 'Entre para minha equipe na {{ env('APP_NAME') }} e comece a lucrar!',
                        url: isUrl ? text : window.location.origin + '/register?ref=' + text
                    });
                } catch (err) {
                    console.log('Share cancelado');
                }
            } else {
                try {
                    await navigator.clipboard.writeText(text);
                    // Aqui você pode disparar um Toast em vez de alert
                    alert("Copiado com sucesso!");
                } catch (err) {
                    alert("Erro ao copiar.");
                }
            }
        }
    </script>
@endsection
