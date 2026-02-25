<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anti-Fraude</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 5px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .stat-card.high-risk {
            border-left-color: #e74c3c;
        }

        .stat-card.medium-risk {
            border-left-color: #f39c12;
        }

        .stat-card.low-risk {
            border-left-color: #f1c40f;
        }

        .stat-card.success {
            border-left-color: #27ae60;
        }

        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-card.high-risk .number {
            color: #e74c3c;
        }

        .stat-card.medium-risk .number {
            color: #f39c12;
        }

        .stat-card.low-risk .number {
            color: #f1c40f;
        }

        .stat-card.success .number {
            color: #27ae60;
        }

        .stat-card .label {
            font-size: 1.1em;
            color: #666;
            font-weight: 500;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .panel h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.4em;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }

        .alert-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .alert-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid;
            background: #f8f9fa;
            transition: all 0.2s ease;
        }

        .alert-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .alert-item.high {
            border-left-color: #e74c3c;
            background: #fdf2f2;
        }

        .alert-item.medium {
            border-left-color: #f39c12;
            background: #fef9f3;
        }

        .alert-item.low {
            border-left-color: #f1c40f;
            background: #fefdf2;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
            color: white;
        }

        .alert-item.high .alert-icon {
            background: #e74c3c;
        }

        .alert-item.medium .alert-icon {
            background: #f39c12;
        }

        .alert-item.low .alert-icon {
            background: #f1c40f;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .alert-description {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .alert-meta {
            font-size: 0.8em;
            color: #999;
        }

        .risk-score {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            color: white;
        }

        .risk-score.high {
            background: #e74c3c;
        }

        .risk-score.medium {
            background: #f39c12;
        }

        .risk-score.low {
            background: #f1c40f;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-indicator.online {
            background: #27ae60;
            box-shadow: 0 0 10px rgba(39, 174, 96, 0.5);
        }

        .status-indicator.warning {
            background: #f39c12;
            box-shadow: 0 0 10px rgba(243, 156, 18, 0.5);
        }

        .status-indicator.danger {
            background: #e74c3c;
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .header h1 {
                font-size: 2em;
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
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
    <div class="dashboard">
        <div class="header">
            <h1>🛡️ Dashboard Anti-Fraude</h1>
            <p>Monitoramento em tempo real de atividades suspeitas</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card high-risk">
                <div class="number" id="highRiskCount">23</div>
                <div class="label">Alertas Críticos</div>
            </div>
            <div class="stat-card medium-risk">
                <div class="number" id="mediumRiskCount">47</div>
                <div class="label">Risco Médio</div>
            </div>
            <div class="stat-card low-risk">
                <div class="number" id="lowRiskCount">12</div>
                <div class="label">Risco Baixo</div>
            </div>
            <div class="stat-card success">
                <div class="number" id="preventedFraud">156</div>
                <div class="label">Fraudes Prevenidas</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="panel">
                <h3>
                    <span class="status-indicator danger"></span>
                    Alertas de Alto Risco
                </h3>
                <div class="alert-list" id="highRiskAlerts">
                    <div class="alert-item high">
                        <div class="alert-icon">⚠️</div>
                        <div class="alert-content">
                            <div class="alert-title">Saque sem Depósito</div>
                            <div class="alert-description">Usuário ID: 1524 solicitou saque de R$ 850 sem histórico de
                                depósitos</div>
                            <div class="alert-meta">Há 15 minutos • <span class="risk-score high">Score: 85</span></div>
                        </div>
                    </div>
                    <div class="alert-item high">
                        <div class="alert-icon">🕷️</div>
                        <div class="alert-content">
                            <div class="alert-title">Rede de Indicação Suspeita</div>
                            <div class="alert-description">Usuário criou 15 indicações em 3 dias com padrões artificiais
                            </div>
                            <div class="alert-meta">Há 1 hora • <span class="risk-score high">Score: 92</span></div>
                        </div>
                    </div>
                    <div class="alert-item medium">
                        <div class="alert-icon">🌐</div>
                        <div class="alert-content">
                            <div class="alert-title">IP Compartilhado</div>
                            <div class="alert-description">7 usuários utilizando o mesmo endereço IP</div>
                            <div class="alert-meta">Há 2 horas • <span class="risk-score medium">Score: 65</span></div>
                        </div>
                    </div>
                    <div class="alert-item high">
                        <div class="alert-icon">💰</div>
                        <div class="alert-content">
                            <div class="alert-title">Manipulação de Saldo</div>
                            <div class="alert-description">Créditos não justificados: R$ 2.450</div>
                            <div class="alert-meta">Há 3 horas • <span class="risk-score high">Score: 88</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h3>
                    <span class="status-indicator warning"></span>
                    Estatísticas de Detecção
                </h3>
                <div class="chart-container">
                    <canvas id="detectionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="panel">
                <h3>
                    <span class="status-indicator online"></span>
                    Ações Rápidas
                </h3>
                <div class="actions">
                    <button class="btn btn-primary" onclick="refreshData()">
                        <span id="refreshBtn">🔄 Atualizar</span>
                    </button>
                    <button class="btn btn-danger" onclick="viewBlockedAccounts()">
                        🚫 Contas Bloqueadas
                    </button>
                    <button class="btn btn-success" onclick="generateReport()">
                        📊 Gerar Relatório
                    </button>
                </div>
            </div>

            <div class="panel">
                <h3>
                    <span class="status-indicator online"></span>
                    Investigações Pendentes
                </h3>
                <div class="alert-list">
                    <div class="alert-item medium">
                        <div class="alert-icon">🔍</div>
                        <div class="alert-content">
                            <div class="alert-title">Usuário #1821</div>
                            <div class="alert-description">Em investigação há 2 dias</div>
                            <div class="alert-meta">Status: Aguardando documentos</div>
                        </div>
                    </div>
                    <div class="alert-item low">
                        <div class="alert-icon">📋</div>
                        <div class="alert-content">
                            <div class="alert-title">Usuário #1654</div>
                            <div class="alert-description">Verificação adicional solicitada</div>
                            <div class="alert-meta">Status: Documentos enviados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuração do gráfico
        const ctx = document.getElementById('detectionChart').getContext('2d');
        const detectionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
                datasets: [{
                    label: 'Alertas Detectados',
                    data: [2, 5, 12, 18, 25, 15, 8],
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Fraudes Prevenidas',
                    data: [1, 3, 8, 12, 20, 12, 6],
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Simulação de dados em tempo real
        function updateStats() {
            document.getElementById('highRiskCount').textContent = Math.floor(Math.random() * 30) + 15;
            document.getElementById('mediumRiskCount').textContent = Math.floor(Math.random() * 50) + 30;
            document.getElementById('lowRiskCount').textContent = Math.floor(Math.random() * 20) + 5;
            document.getElementById('preventedFraud').textContent = Math.floor(Math.random() * 50) + 120;
        }

        // Funções dos botões
        function refreshData() {
            const btn = document.getElementById('refreshBtn');
            btn.innerHTML = '<span class="loading"></span> Atualizando...';

            setTimeout(() => {
                updateStats();
                btn.innerHTML = '🔄 Atualizar';
            }, 2000);
        }

        function viewBlockedAccounts() {
            alert('🚫 Redirecionando para lista de contas bloqueadas...');
            // Aqui você redirecionaria para a página de contas bloqueadas
        }

        function generateReport() {
            alert('📊 Gerando relatório detalhado...');
            // Aqui você geraria um relatório em PDF ou CSV
        }

        // Atualizar dados a cada 30 segundos
        setInterval(updateStats, 30000);

        // Simular novos alertas
        function addNewAlert() {
            const alerts = [
                'Novo usuário com padrão suspeito detectado',
                'Tentativa de saque bloqueada automaticamente',
                'Rede de indicação artificial identificada',
                'Múltiplas contas com dados similares'
            ];

            const randomAlert = alerts[Math.floor(Math.random() * alerts.length)];
            console.log('🔔 Novo alerta:', randomAlert);

            // Aqui você adicionaria o alerta à lista visualmente
        }

        // Simular alertas a cada minuto
        setInterval(addNewAlert, 60000);

        // API simulation - Em produção, estas seriam chamadas reais para sua API
        async function fetchFraudData() {
            // Simulação de chamada para /api/fraud/stats
            return {
                high_risk: Math.floor(Math.random() * 30) + 15,
                medium_risk: Math.floor(Math.random() * 50) + 30,
                low_risk: Math.floor(Math.random() * 20) + 5,
                prevented: Math.floor(Math.random() * 50) + 120
            };
        }

        // Exemplo de como seria a integração real com sua API Laravel
        /*
        async function loadRealData() {
            try {
                const response = await fetch('/api/fraud/dashboard-stats', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                document.getElementById('highRiskCount').textContent = data.high_risk_count;
                document.getElementById('mediumRiskCount').textContent = data.medium_risk_count;
                document.getElementById('lowRiskCount').textContent = data.low_risk_count;
                document.getElementById('preventedFraud').textContent = data.prevented_fraud;
                
                // Atualizar gráfico
                updateChart(data.hourly_stats);
                
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            }
        }
        */

        console.log('🛡️ Dashboard Anti-Fraude inicializado');
        console.log('💡 Sistema detectando atividades suspeitas em tempo real...');
    </script>
</body>

</html>
