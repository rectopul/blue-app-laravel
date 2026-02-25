<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Transações</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-old-lace-50: #fff9ed;
            --color-old-lace-100: #fff2d5;
            --color-old-lace-200: #fee0aa;
            --color-old-lace-300: #fdc974;
            --color-old-lace-400: #fba73c;
            --color-old-lace-500: #f98c16;
            --color-old-lace-600: #ea710c;
            --color-old-lace-700: #c2550c;
            --color-old-lace-800: #9a4312;
            --color-old-lace-900: #7c3912;
            --color-old-lace-950: #431b07;

            --color-mirage-50: #f3f6fc;
            --color-mirage-100: #e7edf7;
            --color-mirage-200: #cad8ed;
            --color-mirage-300: #9bb7de;
            --color-mirage-400: #6591cb;
            --color-mirage-500: #4173b6;
            --color-mirage-600: #305999;
            --color-mirage-700: #28487c;
            --color-mirage-800: #243e68;
            --color-mirage-900: #233657;
            --color-mirage-950: #101828;

            --color-matisse-50: #f3f6fc;
            --color-matisse-100: #e7eef7;
            --color-matisse-200: #c9daee;
            --color-matisse-300: #99bce0;
            --color-matisse-400: #6299ce;
            --color-matisse-500: #3e7cb9;
            --color-matisse-600: #2f66a3;
            --color-matisse-700: #254e7f;
            --color-matisse-800: #22446a;
            --color-matisse-900: #213a59;
            --color-matisse-950: #16253b;

            --color-curious-blue-50: #f0faff;
            --color-curious-blue-100: #e0f3fe;
            --color-curious-blue-200: #b9e9fe;
            --color-curious-blue-300: #7cd9fd;
            --color-curious-blue-400: #36c7fa;
            --color-curious-blue-500: #0cb0eb;
            --color-curious-blue-600: #009ada;
            --color-curious-blue-700: #0171a3;
            --color-curious-blue-800: #065f86;
            --color-curious-blue-900: #0b4f6f;
            --color-curious-blue-950: #07324a;
        }
    </style>
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
            background: linear-gradient(180deg, #fff2d5 0%, #fee0aa 60%);
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
            color: #2f66a3;
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
            color: #2f66a3;
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
            color: #2f66a3;
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
            color: #2f66a3;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .balance-amount {
            color: #2f66a3;
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
            color: #2f66a3;
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
            background: #fba73c;
            border: 2px solid #fba73c;
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
            background: linear-gradient(to bottom, #fdc974, #fba73c);
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
            color: #254e7f;
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
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <button class="back-btn" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="header-title">Histórico Flexível</h1>
                <button class="menu-btn">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <!-- Balance Card -->
            <div class="balance-card">
                <div class="balance-label">Ativos Totais (R$)</div>
                <div class="balance-amount">0,00</div>

                <div class="action-buttons">
                    <button class="action-btn active" onclick="switchTab(this, 'deposits')">
                        Depósitos
                    </button>
                    <button class="action-btn" onclick="switchTab(this, 'withdrawals')">
                        Saques
                    </button>
                    <button class="action-btn" onclick="switchTab(this, 'ledger')">
                        Histórico
                    </button>
                </div>

                <button class="invest-btn" onclick="goToInvestments()">
                    <i class="fas fa-chart-line"></i>
                    Investir
                </button>
            </div>
        </div>

        <!-- History Section -->
        <div class="history-section">
            <h2 class="section-title">
                <i class="fas fa-history"></i>
                Transações Recentes
            </h2>

            <!-- Loading State -->
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Carregando transações...</p>
            </div>

            <!-- Deposits List -->
            <div id="deposits" class="transaction-list active">
                <!-- Sample Deposit Transactions -->
                @foreach ($deposits as $deposit)
                    <div class="transaction-card">
                        <div class="transaction-header">
                            <span class="transaction-id">S.N: {{ $deposit->id }}</span>
                            @if ($deposit->status === 'pending')
                                <span class="transaction-status status-pending">Pendente</span>
                            @else
                                <span class="transaction-status status-approved">Aprovado</span>
                            @endif
                        </div>
                        <div class="transaction-main">
                            <div class="transaction-info">
                                <div class="transaction-type">
                                    <div class="type-icon deposit-icon">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    Depósito
                                </div>
                                <div class="transaction-date">Hoje, 14:30</div>
                            </div>
                            <div class="transaction-amount">+ R$ {{ $deposit->amount }}</div>
                        </div>
                    </div>
                @endforeach

                @if ($deposits->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3 class="empty-title">Nenhuma transação encontrada</h3>
                        <p class="empty-description">Suas transações aparecerão aqui quando você começar a investir.</p>
                    </div>
                @endif
            </div>

            <!-- Withdrawals List -->
            <div id="withdrawals" class="transaction-list">
                @foreach ($withdrawals as $withdraw)
                    <div class="transaction-card">
                        <div class="transaction-header">
                            <span class="transaction-id">S.N: {{ $withdraw->id }}</span>
                            @if ($withdraw->status === 'pending')
                                <span class="transaction-status status-pending">Pendente</span>
                            @else
                                <span class="transaction-status status-approved">Aprovado</span>
                            @endif
                        </div>
                        <div class="transaction-main">
                            <div class="transaction-info">
                                <div class="transaction-type">
                                    <div class="type-icon withdrawal-icon">
                                        <i class="fas fa-minus"></i>
                                    </div>
                                    Saque
                                </div>
                                <div class="transaction-date">Hoje, 09:15</div>
                            </div>
                            <div class="transaction-amount">- R$ $withdraw->amount</div>
                        </div>
                    </div>
                @endforeach

                @if ($withdrawals->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3 class="empty-title">Nenhuma transação encontrada</h3>
                        <p class="empty-description">Suas transações aparecerão aqui quando você começar a investir.</p>
                    </div>
                @endif
            </div>

            <!-- Ledger List -->
            <div id="ledger" class="transaction-list">
                @foreach ($commissions as $comission)
                    <div class="transaction-card">
                        <div class="transaction-header">
                            <span class="transaction-id">Informação Extra</span>
                            <span class="transaction-status status-approved">Incluído</span>
                        </div>
                        <div class="transaction-main">
                            <div class="transaction-info">
                                <div class="transaction-type">
                                    <div class="type-icon ledger-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    Bônus de Referência
                                </div>
                                <div class="transaction-date">Hoje, 12:00</div>
                            </div>
                            <div class="transaction-amount">+ R$ {{ $comission->amount }}</div>
                        </div>
                    </div>
                @endforeach


                @foreach ($ledgers as $ledger)
                    <div class="transaction-card">
                        <div class="transaction-header">
                            <span class="transaction-id">Informação Extra</span>
                            <span class="transaction-status status-approved">Incluído</span>
                        </div>
                        <div class="transaction-main">
                            <div class="transaction-info">
                                <div class="transaction-type">
                                    <div class="type-icon ledger-icon">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                    Rendimento de Investimento
                                </div>
                                <div class="transaction-date">Ontem, 18:00</div>
                            </div>
                            <div class="transaction-amount">+ R$ {{ $ledger }}</div>
                        </div>
                    </div>
                @endforeach
                @if ($ledgers->isEmpty() && $commissions->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3 class="empty-title">Nenhuma transação encontrada</h3>
                        <p class="empty-description">Suas transações aparecerão aqui quando você começar a investir.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        function switchTab(button, tabName) {
            // Remove active class from all buttons
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Add active class to clicked button
            button.classList.add('active');

            // Hide all transaction lists
            document.querySelectorAll('.transaction-list').forEach(list => {
                list.classList.remove('active');
            });

            // Show selected tab with loading animation
            showLoading();

            setTimeout(() => {
                hideLoading();
                document.getElementById(tabName).classList.add('active');
            }, 800);
        }

        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }

        function goBack() {
            // Simular navegação de volta
            window.location.href = "{{ route('dashboard') }}";
        }

        function goToInvestments() {
            console.log('Navegando para investimentos...');
            // window.location.href = '/investments';
        }

        // Simular carregamento de dados ao carregar a página
        window.addEventListener('load', function() {
            setTimeout(() => {
                // Simular chamada AJAX
                console.log('Dados carregados com sucesso');
            }, 1000);
        });

        // Adicionar efeito de ripple nos botões
        document.querySelectorAll('.action-btn, .invest-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    </script>
    @include('partials.dmk.footer_menu')
    <style>
        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>

</html>
