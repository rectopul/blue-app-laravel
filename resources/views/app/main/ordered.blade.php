@extends('layouts.master')
@section('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #fff5f1 0%, #ffffff 100%);
            min-height: 100vh;
            color: #1a1a1a;
        }

        .container {
            max-width: 430px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 30px rgba(249, 115, 22, 0.1);
            position: relative;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            padding: 60px 20px 30px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .back-btn {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .header-title {
            color: white;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .menu-btn {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        /* Balance Card */
        .balance-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 24px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
        }

        .balance-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .balance-amount {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 12px;
            padding: 12px 8px;
            color: #f97316;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.2);
        }

        .action-btn.active {
            background: white;
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.25);
            transform: translateY(-1px);
        }

        .invest-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 16px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .invest-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* History Section */
        .history-section {
            padding: 30px 20px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(to bottom, #f97316, #ea580c);
            border-radius: 2px;
        }

        /* Transaction Cards */
        .transaction-list {
            display: none;
        }

        .transaction-list.active {
            display: block;
        }

        .transaction-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(249, 115, 22, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .transaction-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #f97316, #ea580c);
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(249, 115, 22, 0.15);
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .transaction-id {
            font-size: 12px;
            color: #666;
            font-weight: 500;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .transaction-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d1f2eb;
            color: #0f6848;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .transaction-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transaction-info {
            flex: 1;
        }

        .transaction-type {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .type-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
        }

        .deposit-icon {
            background: linear-gradient(45deg, #10b981, #059669);
        }

        .withdrawal-icon {
            background: linear-gradient(45deg, #ef4444, #dc2626);
        }

        .ledger-icon {
            background: linear-gradient(45deg, #f97316, #ea580c);
        }

        .transaction-amount {
            font-size: 20px;
            font-weight: 700;
            color: #f97316;
        }

        .transaction-date {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #666;
            font-size: 32px;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .empty-description {
            color: #666;
            font-size: 14px;
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .transaction-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .transaction-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .transaction-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .transaction-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                max-width: 100%;
            }

            .header {
                padding: 50px 16px 24px;
            }

            .balance-amount {
                font-size: 28px;
            }

            .history-section {
                padding: 24px 16px;
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #f97316;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <div class="w-[420px] mx-auto bg-white min-h-screen flex flex-col font-sans">
        @include('partials.header')

        <div class="p-2">

            @foreach ($packages as $package)
                <div
                    class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 mt-2">
                    <!-- Cabeçalho do Card -->
                    <div class="bg-blue-400 p-4 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 rounded-full bg-white flex items-center justify-center overflow-hidden mr-3 border-2 border-white shadow-sm">
                                    <img src="/public{{ $package->photo }}" alt="{{ $package->name }}"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $package->name }}</h3>
                                </div>
                            </div>

                            <!-- Botão de Status em Processamento -->
                            <div class="bg-yellow-100 border border-yellow-300 rounded-full px-3 py-1 flex items-center">
                                <!-- Loading Spinner -->
                                <div
                                    class="animate-spin rounded-full h-4 w-4 border-2 border-yellow-500 border-t-transparent mr-2">
                                </div>
                                <span class="text-yellow-700 text-xs font-medium">Em Processamento</span>
                            </div>
                        </div>
                    </div>

                    <!-- Corpo do Card -->
                    <div class="p-4">
                        <!-- Badge de Segurança -->
                        <div class="flex items-center mb-4 text-gray-600 bg-gray-50 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="text-xs font-medium">Investimento verificado e seguro</span>
                        </div>

                        <!-- Informações do Pacote -->
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 flex items-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Valor do Investimento
                                </p>
                                <p class="font-bold text-gray-800 text-lg pl-6">{{ price($package->price) }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 flex items-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Retorno Total
                                </p>
                                <p class="font-bold text-gray-800 text-lg pl-6">
                                    @php
                                        $total_return = $package->commission_with_avg_amount * $package->valididty;
                                    @endphp
                                    {{ price($total_return) }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500 flex items-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Duração
                                    </p>
                                    <p class="font-semibold text-gray-700 pl-6">{{ $package->valididty }} dias</p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 flex items-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Retorno Diário
                                    </p>
                                    <p class="font-semibold text-green-600 pl-6">
                                        {{ $package->commission_with_avg_amount }}% ao dia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($packages->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3 class="empty-title">Nenhum pacote encontrado</h3>
                    <p class="empty-description">Seus pacotes de investimento apareceção aqui.</p>
                </div>
            @endif
        </div>
    </div>

    @include('alert-message')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script>
        window.onload = function() {
            document.querySelector('.van-toast--loading').style.display = 'none';
        };

        function rcv() {
            document.querySelector('.rcv').innerHTML = 'Receiving...'
            $.ajax({
                url: "{{ route('user.received.amount') }}",
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    document.querySelector('.rcv').innerHTML = 'Receive'
                    document.querySelector('.ttff').innerHTML = 'Rs.0.00'
                    message(res.message)
                }
            });
        }
    </script>
@endsection
