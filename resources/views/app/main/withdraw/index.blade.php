<html lang="en" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ env('ÁPP_NAME') }}</title>
    <style>
        :root {
            --primary: #FF6B00;
            --primary-light: #FF8F3F;
            --primary-dark: #E05A00;
            --primary-ultra-light: #FFF0E6;
            --text-dark: #333333;
            --text-light: #FFFFFF;
            --text-gray: #666666;
            --text-light-gray: #999999;
            --border-color: #E0E0E0;
            --bg-light: #FFFFFF;
            --bg-gray: #F8F8F8;
            --shadow: 0 4px 12px rgba(255, 107, 0, 0.15);
            --danger: #FF3B30;
            --success: #34C759;
            --radius: 12px;
            --radius-sm: 8px;
            --font-main: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            background-color: #FAFAFA;
            color: var(--text-dark);
            line-height: 1.5;
        }

        .navigation {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: linear-gradient(90deg, #FF6B00, #FF8F3F);
            color: white;
            display: flex;
            align-items: center;
            padding: 0 16px;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(255, 107, 0, 0.2);
        }

        .navigation-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
        }

        .tools {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .icon {
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .back-icon::before {
            content: "←";
            font-size: 20px;
            font-weight: bold;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 72px 16px 24px;
        }

        .assets {
            background: linear-gradient(135deg, #FF6B00, #FF8F3F);
            border-radius: var(--radius);
            color: white;
            padding: 24px 20px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .assets .name {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .assets .amount {
            font-size: 32px;
            font-weight: bold;
        }

        .card {
            background: var(--bg-light);
            border-radius: var(--radius);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            padding: 24px;
            margin: 24px 0;
            border: 1px solid var(--border-color);
        }

        .card-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: var(--primary);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-gray);
        }

        .form-select-container {
            position: relative;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 16px;
            background-color: var(--bg-light);
            appearance: none;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.2);
        }

        .select-arrow {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            width: 12px;
            height: 12px;
            border-right: 2px solid var(--text-gray);
            border-bottom: 2px solid var(--text-gray);
            transform: translateY(-70%) rotate(45deg);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.2);
        }

        .input-with-prefix {
            position: relative;
        }

        .input-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dark);
            font-weight: 500;
        }

        .input-with-prefix .form-input {
            padding-left: 36px;
        }

        .balance-info {
            font-size: 12px;
            color: var(--text-light-gray);
            margin-top: 4px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            flex: 1;
            border: none;
            padding: 14px 0;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .btn-cancel {
            background-color: #F2F2F2;
            color: var(--text-gray);
        }

        .btn-cancel:hover {
            background-color: #E5E5E5;
        }

        .btn-primary {
            background: linear-gradient(90deg, #FF6B00, #FF8F3F);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #E05A00, #FF6B00);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(255, 107, 0, 0.3);
        }

        .btn-primary:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        .info-box {
            background-color: var(--primary-ultra-light);
            border: 1px solid rgba(255, 107, 0, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px;
            margin-top: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .info-box-icon {
            color: var(--primary);
            font-size: 20px;
            margin-top: 2px;
        }

        .info-box-text {
            font-size: 14px;
            color: var(--text-gray);
        }

        .fee-box {
            margin-top: 16px;
        }

        .reminder-section {
            margin-top: 32px;
        }

        .reminder-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .reminder-icon {
            width: 20px;
            height: 20px;
        }

        .reminder-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        .reminder-list {
            list-style-type: none;
            padding-left: 4px;
        }

        .reminder-list p {
            position: relative;
            padding-left: 16px;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-gray);
        }

        .reminder-list p:before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--primary);
        }

        /* Loader */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none;
        }

        /* Alert styles */
        .swal-mobile {
            width: 90% !important;
            max-width: 300px !important;
            padding: 1.5rem !important;
            font-size: 0.9rem !important;
            border-radius: var(--radius) !important;
        }

        .swal2-confirm {
            background: linear-gradient(90deg, #FF6B00, #FF8F3F) !important;
            border-radius: var(--radius-sm) !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="navigation">
        <div class="navigation-content">Withdraw
            <div class="tools">
                <div class="icon back-icon" onclick="window.location.href='{{ route('dashboard') }}'" aria-label="Back">
                </div>
                <div onclick="window.location.href='{{ route('history') }}'" class="icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16.25 2.5H3.75C3.0625 2.5 2.5 3.0625 2.5 3.75V16.25C2.5 16.9375 3.0625 17.5 3.75 17.5H16.25C16.9375 17.5 17.5 16.9375 17.5 16.25V3.75C17.5 3.0625 16.9375 2.5 16.25 2.5ZM7.1875 14.0625H5.3125V7.8125H7.1875V14.0625ZM10.9375 14.0625H9.0625V5.9375H10.9375V14.0625ZM14.6875 14.0625H12.8125V9.6875H14.6875V14.0625Z"
                            fill="white" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="assets">
            <div class="name">Saldo: </div>
            <div class="amount">{{ price(user()->balance) }}</div>
        </div>

        <div class="card">
            <h3 class="card-header">Saque via PIX</h3>
            <form id="withdrawForm">
                <div class="form-group">
                    <label class="form-label">Tipo de Chave PIX</label>
                    <div class="form-select-container">
                        <select id="pixType" class="form-select" required>
                            <option value="">Selecione o tipo de chave</option>
                            <option value="CPF">CPF</option>
                            <option value="EMAIL">E-mail</option>
                            <option value="PHONE">Telefone</option>
                            <option value="RANDOM">Chave Aleatória</option>
                        </select>
                        <span class="select-arrow"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Chave PIX</label>
                    <input id="pixKey" type="text" class="form-input" placeholder="Digite sua chave PIX"
                        required />
                </div>

                <div class="form-group">
                    <label class="form-label">Nome completo</label>
                    <input id="name" type="text" class="form-input" placeholder="Digite seu nome completo"
                        required />
                </div>

                <div class="form-group">
                    <label class="form-label">CPF</label>
                    <input id="document" type="text" class="form-input" placeholder="Digite seu CPF" required />
                </div>

                <div class="form-group">
                    <label class="form-label">Valor do saque</label>
                    <div class="input-with-prefix">
                        <span class="input-prefix">R$</span>
                        <input id="amount" type="text" class="form-input" placeholder="0,00" required />
                    </div>
                    <p class="balance-info">Saldo disponível: R$ {{ $user->balance }}</p>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-cancel">Cancelar</button>
                    <button type="submit" id="submitButton" class="btn btn-primary">Confirmar Saque</button>
                </div>

                <div class="info-box">
                    <div class="info-box-icon">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 1.5C4.86 1.5 1.5 4.86 1.5 9C1.5 13.14 4.86 16.5 9 16.5C13.14 16.5 16.5 13.14 16.5 9C16.5 4.86 13.14 1.5 9 1.5ZM9.75 13.5H8.25V12H9.75V13.5ZM9.75 10.5H8.25V4.5H9.75V10.5Z"
                                fill="#FF6B00" />
                        </svg>
                    </div>
                    <p class="info-box-text">
                        Sua transação é segura. Todos os dados são criptografados e protegidos conforme as normas do
                        Banco Central.
                    </p>
                </div>

                <div class="info-box fee-box">
                    <div class="info-box-icon">%</div>
                    <p class="info-box-text">
                        Taxa de 5% de saque.
                    </p>
                </div>
            </form>
        </div>

        <div class="reminder-section">
            <div class="reminder-header">
                <svg class="reminder-icon" width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9 1.5C4.86 1.5 1.5 4.86 1.5 9C1.5 13.14 4.86 16.5 9 16.5C13.14 16.5 16.5 13.14 16.5 9C16.5 4.86 13.14 1.5 9 1.5ZM9.75 13.5H8.25V12H9.75V13.5ZM9.75 10.5H8.25V4.5H9.75V10.5Z"
                        fill="#FF6B00" />
                </svg>
                <span class="reminder-title">Warm reminder</span>
            </div>
            <div class="reminder-list">
                <p>1.Minimum withdrawal is:- 3USDT</p>
                <p>2. To maintain the stable operation of the platform, a fixed gas fee of 1$ will be charged for each
                    withdrawal.</p>
                <p>4. The withdrawal will be confirmed and reviewed by multiple nodes and the arrival time is 1-30
                    minutes of system time.</p>
            </div>
        </div>
    </div>

    <!-- Loader (hidden by default) -->
    <div id="loader" class="loader-overlay hidden">
        <div class="loader"></div>
    </div>

    <script>
        document.getElementById('withdrawForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loader
            const loader = document.getElementById('loader');
            const submitButton = document.getElementById('submitButton');
            loader.classList.remove('hidden');
            submitButton.disabled = true;

            // Collect form data
            const formData = {
                amount: document.getElementById('amount').value.replace(',', '.'),
                document: document.getElementById('document').value,
                name: document.getElementById('name').value,
                pix_type: document.getElementById('pixType').value,
                pix_key: document.getElementById('pixKey').value
            };

            // Fetch API request
            fetch("{{ route('api.withdraw.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer ' + "{{ $token }}"
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loader
                    loader.classList.add('hidden');
                    submitButton.disabled = false;

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal-mobile',
                                confirmButton: 'swal2-confirm'
                            }
                        }).then(() => {
                            window.location.href = "{{ route('dashboard') }}";
                        });
                    } else {
                        // Error alert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invista!',
                            text: data.message,
                            timer: 3000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'swal-mobile'
                            }
                        });
                    }
                })
                .catch(error => {
                    // Hide loader
                    loader.classList.add('hidden');
                    submitButton.disabled = false;

                    // General error alert
                    Swal.fire({
                        icon: 'warning',
                        title: 'Erro na solicitação',
                        text: 'Tente novamente.',
                        timer: 3000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'swal-mobile'
                        }
                    });
                });
        });
    </script>
</body>

</html>
