<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InvestLoop • Investimentos</title>

    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js via CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Quicksand', sans-serif;
        }

        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Ocultar barra de rolagem mas manter funcionalidade */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen bg-[#F0F7FF] text-slate-800 font-sans antialiased">
    {{-- Container com “cara de app” --}}
    <div class="mx-auto min-h-screen max-w-[420px] bg-white shadow-2xl relative overflow-x-hidden border-x border-slate-100">
        @yield('content')

        {{-- Sistema de Gamificação (Ovos Escondidos) --}}
        <x-hidden-egg />
    </div>

    @stack('scripts')
</body>

</html>
