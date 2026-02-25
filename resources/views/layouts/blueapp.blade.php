{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>InvestLoop • Dashboard</title>

    {{-- Tailwind via CDN (rápido pra começar). Em produção, use Vite/build. --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

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

<body class="min-h-screen bg-[#EEF4F9] text-slate-800">
    {{-- Container com “cara de app” (como na inspiração) --}}
    <div class="mx-auto min-h-screen max-w-[420px]">
        {{-- Top background (gradiente tipo céu) --}}
        @yield('content')

    </div>
</body>

</html>
