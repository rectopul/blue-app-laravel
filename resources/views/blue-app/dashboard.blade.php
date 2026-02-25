@extends('layouts.blueapp')

@section('content')
    @php
        // Mock de dados (troque pelos seus models)
        $userName = $userName ?? 'Rogério';
        $walletBalance = $walletBalance ?? 12850.75;
        $dailyEarningsToday = $dailyEarningsToday ?? 62.3;
        $dailyRate = $dailyRate ?? 1.2; // %

        $plans = $plans ?? [
            [
                'name' => 'Starter Daily',
                'days' => 7,
                'rate' => 1.1,
                'min' => 50,
                'tag' => 'Baixo risco',
                'accent' => '#4FAA F7',
            ],
            [
                'name' => 'Prime Daily',
                'days' => 15,
                'rate' => 1.3,
                'min' => 200,
                'tag' => 'Equilibrado',
                'accent' => '#2C95EF',
            ],
            [
                'name' => 'Turbo Daily',
                'days' => 30,
                'rate' => 1.6,
                'min' => 500,
                'tag' => 'Alta performance',
                'accent' => '#0E7FE7',
            ],
        ];

        $investments = $investments ?? [
            [
                'title' => 'Prime Daily • 15 dias',
                'subtitle' => 'Rendimento diário automático',
                'amount' => 1200,
                'status' => 'ATIVO',
                'badge' => 2,
                'time' => 'Hoje',
                'progress' => 38,
            ],
            [
                'title' => 'Starter Daily • 7 dias',
                'subtitle' => 'Liberar saque ao concluir',
                'amount' => 300,
                'status' => 'ATIVO',
                'badge' => 1,
                'time' => 'Ontem',
                'progress' => 86,
            ],
            [
                'title' => 'Turbo Daily • 30 dias',
                'subtitle' => 'Reinvestimento opcional',
                'amount' => 2500,
                'status' => 'PENDENTE',
                'badge' => 0,
                'time' => '01:04',
                'progress' => 12,
            ],
        ];

        $categories = $categories ?? [
            ['label' => 'Curto Prazo', 'icon' => '📈'],
            ['label' => 'Renda Diária', 'icon' => '🗓️'],
            ['label' => 'Reinvestir', 'icon' => '🔁'],
            ['label' => 'Retirar', 'icon' => '🏦'],
            ['label' => 'VIP', 'icon' => '💎'],
        ];
    @endphp

    <div
        class="relative overflow-hidden rounded-b-[34px] bg-gradient-to-b from-[#CFE7FF] via-[#B9DDFF] to-[#EEF4F9] px-5 pt-6 pb-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-600/80">Bem-vindo,</p>
                <h1 class="text-[22px] font-semibold tracking-tight">
                    Invest<span class="text-slate-900">Loop</span><span class="text-[#2C95EF]">.</span>
                </h1>
            </div>

            {{-- Notificação --}}
            <button
                class="relative grid h-11 w-11 place-items-center rounded-2xl bg-white/90 shadow-sm ring-1 ring-black/5 active:scale-[0.98]">
                <span class="text-lg">🔔</span>
                <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-[#2C95EF] ring-2 ring-white"></span>
            </button>
        </div>

        {{-- Card Azul grande (fiel ao layout da imagem) --}}
        <div
            class="mt-5 rounded-[22px] bg-gradient-to-b from-[#57B0FF] to-[#2C95EF] px-4 pt-4 pb-4 shadow-lg shadow-blue-500/20 ring-1 ring-white/20">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-white/90">Meus atalhos</p>
                <button class="grid h-8 w-8 place-items-center rounded-xl bg-white/20 text-white ring-1 ring-white/20">
                    <span class="text-xl leading-none">…</span>
                </button>
            </div>

            {{-- “Stories” circulares (adaptado para categorias de investimento) --}}
            <div class="mt-4 flex items-center gap-3 overflow-hidden pb-1 pt-1">
                {{-- Add --}}
                <button class="flex shrink-0 flex-col items-center gap-2">
                    <span
                        class="grid h-[58px] w-[58px] place-items-center rounded-full border-2 border-dashed border-white/60 bg-white/10 text-white">

                        <span class="text-xl">📊</span>
                    </span>
                    <span class="text-[11px] text-white/90">Rede</span>
                </button>

                @foreach ($categories as $cat)
                    <button class="flex shrink-0 flex-col items-center gap-2">
                        <span
                            class="grid h-[58px] w-[58px] place-items-center rounded-full bg-white/15 ring-2 ring-white/60">
                            <span class="text-xl">{{ $cat['icon'] }}</span>
                        </span>
                        <span class="max-w-[76px] truncate text-[11px] text-white/90">{{ $cat['label'] }}</span>
                    </button>
                @endforeach
            </div>

        </div>

        {{-- Carteira rápida (abaixo do card azul, ainda no topo) --}}
        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="rounded-2xl bg-white/80 p-4 shadow-sm ring-1 ring-black/5">
                <p class="text-xs text-slate-500">Saldo disponível</p>
                <p class="mt-1 text-lg font-semibold">
                    R$ {{ number_format($walletBalance, 2, ',', '.') }}
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Hoje: <span class="font-medium text-[#0E7FE7]">+R$
                        {{ number_format($dailyEarningsToday, 2, ',', '.') }}</span>
                </p>
            </div>

            <div class="rounded-2xl bg-white/80 p-4 shadow-sm ring-1 ring-black/5">
                <p class="text-xs text-slate-500">Taxa diária média</p>
                <p class="mt-1 text-lg font-semibold">
                    {{ number_format($dailyRate, 1, ',', '.') }}% <span
                        class="text-sm font-medium text-slate-500">/dia</span>
                </p>
                <div class="mt-2 flex gap-2">
                    <a href="#"
                        class="inline-flex items-center justify-center rounded-xl bg-[#2C95EF] px-3 py-2 text-xs font-medium text-white shadow-sm active:scale-[0.98]">
                        Investir
                    </a>
                    <a href="#"
                        class="inline-flex items-center justify-center rounded-xl bg-[#EAF4FF] px-3 py-2 text-xs font-medium text-[#2C95EF] ring-1 ring-black/5 active:scale-[0.98]">
                        Retirar
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Conteúdo principal --}}
    <div class="px-5 pb-28 pt-4">
        {{-- Tabs (bem parecido com “All / Unread / Favourite / Group”) --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <button
                class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-2 text-sm font-medium shadow-sm ring-1 ring-black/5">
                Todos
                <span
                    class="grid h-5 min-w-5 place-items-center rounded-full bg-[#EAF4FF] px-1 text-xs text-[#2C95EF]">✓</span>
            </button>

            <button
                class="inline-flex items-center gap-2 rounded-2xl bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 ring-1 ring-black/5">
                Rendendo
                <span
                    class="grid h-5 min-w-5 place-items-center rounded-full bg-[#2C95EF] px-1 text-xs text-white">32</span>
            </button>

            <button
                class="inline-flex items-center gap-2 rounded-2xl bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 ring-1 ring-black/5">
                Favoritos
            </button>

            <button
                class="inline-flex items-center gap-2 rounded-2xl bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 ring-1 ring-black/5">
                Grupos
            </button>
        </div>

        {{-- Seção: Planos para investir (cards horizontais) --}}
        <div class="mt-5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-800">Investimentos disponíveis</h2>
                <a href="#" class="text-sm font-medium text-[#2C95EF]">Ver todos</a>
            </div>

            <div class="mt-3 flex gap-3 overflow-x-auto pb-1">
                @foreach ($plans as $plan)
                    <a href="#"
                        class="w-[240px] shrink-0 rounded-[22px] bg-white p-4 shadow-sm ring-1 ring-black/5 active:scale-[0.99]">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-semibold">{{ $plan['name'] }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $plan['days'] }} dias •
                                    {{ number_format($plan['rate'], 1, ',', '.') }}%/dia</p>
                            </div>
                            <span class="rounded-xl bg-[#EAF4FF] px-2 py-1 text-xs font-medium text-[#2C95EF]">
                                {{ $plan['tag'] }}
                            </span>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-xs text-slate-500">A partir de</p>
                            <p class="text-sm font-semibold">R$ {{ number_format($plan['min'], 0, ',', '.') }}</p>
                        </div>

                        <div class="mt-3">
                            <div class="h-2 w-full rounded-full bg-slate-100">
                                <div class="h-2 w-[62%] rounded-full bg-[#2C95EF]"></div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">
                                Rendimento diário creditado automaticamente.
                            </p>
                        </div>

                        <button
                            class="mt-4 w-full rounded-2xl bg-[#2C95EF] py-2.5 text-sm font-semibold text-white shadow-sm active:scale-[0.98]">
                            Aplicar agora
                        </button>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Seção: Investimentos em andamento (lista estilo chat) --}}
        <div class="mt-6">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-800">Meus investimentos</h2>
                <button class="grid h-9 w-9 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                    <span class="text-xl leading-none">…</span>
                </button>
            </div>

            <div class="mt-3 space-y-3">
                @foreach ($investments as $inv)
                    <a href="#"
                        class="flex items-center gap-3 rounded-[22px] bg-white p-4 shadow-sm ring-1 ring-black/5 active:scale-[0.99]">
                        {{-- Avatar/ícone circular (como foto no chat) --}}
                        <div class="relative">
                            <div
                                class="grid h-12 w-12 place-items-center rounded-full bg-[#EAF4FF] text-[#2C95EF] ring-1 ring-black/5">
                                <span class="text-lg">💼</span>
                            </div>
                            {{-- mini “status dot” --}}
                            <span
                                class="absolute -bottom-0.5 -right-0.5 h-4 w-4 rounded-full bg-[#2C95EF] ring-2 ring-white"></span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <p class="truncate text-sm font-semibold text-slate-800">{{ $inv['title'] }}</p>
                                <p class="shrink-0 text-xs text-slate-500">{{ $inv['time'] }}</p>
                            </div>

                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="truncate text-xs text-slate-500">{{ $inv['subtitle'] }}</p>

                                @if (($inv['badge'] ?? 0) > 0)
                                    <span
                                        class="grid h-5 min-w-5 place-items-center rounded-full bg-[#2C95EF] px-1 text-[11px] font-semibold text-white">
                                        {{ $inv['badge'] }}
                                    </span>
                                @endif
                            </div>

                            {{-- progress bar pequena, fiel ao estilo clean --}}
                            <div class="mt-3 flex items-center gap-3">
                                <div class="h-2 flex-1 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full bg-[#2C95EF]"
                                        style="width: {{ (int) $inv['progress'] }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-600">{{ (int) $inv['progress'] }}%</span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-xs text-slate-500">
                                    Valor: <span class="font-semibold text-slate-800">R$
                                        {{ number_format($inv['amount'], 2, ',', '.') }}</span>
                                </p>

                                <span
                                    class="rounded-xl px-2 py-1 text-xs font-semibold
                                        {{ $inv['status'] === 'ATIVO' ? 'bg-[#EAF4FF] text-[#2C95EF]' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $inv['status'] }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom Navigation (igual vibe da imagem) --}}
    <div class="fixed bottom-0 left-0 right-0 mx-auto max-w-[420px] bg-[#EEF4F9] px-5 pb-6 pt-3">
        <div class="flex items-center justify-between gap-2">
            <button class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                <span class="text-xl">🏠</span>
            </button>

            {{-- Botão central (New Chat -> Novo Investimento) --}}
            <button
                class="flex h-12 flex-1 items-center justify-center gap-2 rounded-[18px] bg-[#2C95EF] px-5 text-sm font-semibold text-white shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                <span class="text-lg leading-none">＋</span>
                Novo depósito
            </button>

            <button class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                <span class="text-xl">👤</span>
            </button>
        </div>
    </div>
@endsection
