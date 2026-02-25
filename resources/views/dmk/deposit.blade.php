@extends('layouts.dmk')

@section('content')
    <div class="w-full h-48 bg-cover bg-center relative"
        style="background-image: url('{{ asset(main_root() . '/assets/img/bankbg.jpg') }}')">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-50"></div>

        <div class="relative z-10 px-4 pt-6 flex items-center justify-between text-white">
            <a href="{{ route('dashboard') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md">
                <span class="material-symbols-outlined !text-xl">arrow_back</span>
            </a>
            <h1 class="text-lg font-black uppercase tracking-widest">Depósito</h1>
            <a href="{{ route('history') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md">
                <span class="material-symbols-outlined !text-xl">list_alt</span>
            </a>
        </div>
    </div>

    <div class="px-4 -mt-16 relative z-20 mb-24">

        <div class="w-full bg-white rounded-[24px] p-5 shadow-xl shadow-slate-200/50 mb-4 border border-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-elm-50 flex items-center justify-center text-elm-600">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Saldo Disponível</p>
                    <p class="text-xl font-black text-slate-800">{{ price($balance) }}</p>
                </div>
            </div>
        </div>

        <div class="w-full bg-white rounded-[28px] p-6 shadow-sm border border-slate-100 mb-6">
            <label for="amount"
                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Quanto deseja
                depositar?</label>

            <div class="relative flex items-center justify-center mb-8">
                <span class="text-2xl font-black text-slate-300 mr-2">{{ currency() }}</span>
                <input type="number" name="amount" id="amount"
                    class="w-full max-w-[200px] text-4xl font-black text-slate-900 outline-none text-center bg-transparent placeholder:text-slate-100"
                    placeholder="0,00">
            </div>

            <div class="grid grid-cols-4 gap-2">
                @foreach ([50, 80, 120, 200, 400, 800, 1000, 1200] as $val)
                    <button onclick="getAmount(this, {{ $val }})"
                        class="deposit-amount-btn py-3 text-sm font-bold rounded-xl border border-slate-100 text-slate-600 hover:border-elm-500 hover:text-elm-600 hover:bg-elm-50 transition-all active:scale-90">
                        {{ $val }}
                    </button>
                @endforeach
            </div>
        </div>

        <button id="btnConfirmarDeposito" onclick="payment()"
            class="w-full bg-slate-900 hover:bg-elm-600 active:scale-95 text-white font-black py-5 px-6 rounded-[20px] shadow-xl shadow-slate-200 transition-all flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed">

            <div id="loaderIcon" class="hidden">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            <span id="btnText" class="flex items-center gap-3">
                <span class="material-symbols-outlined">bolt</span>
                Confirmar e Gerar PIX
            </span>
        </button>
    </div>

    <div id="pixModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closePixModal()"></div>

        <div
            class="relative w-full max-w-md bg-white rounded-[32px] overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
            <div class="bg-elm-600 p-6 text-white text-center">
                <h3 class="text-xl font-black uppercase tracking-widest">Pagamento PIX</h3>
                <p class="text-elm-100 text-xs opacity-80">Escaneie o QR Code abaixo</p>
            </div>

            <div class="p-6 bg-white relative">
                <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-900/60 rounded-full z-20"></div>
                <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-900/60 rounded-full z-20"></div>

                <div
                    class="flex flex-col md:flex-row gap-6 items-center border-2 border-slate-100 rounded-[24px] p-6 border-dashed">

                    <div class="flex-shrink-0 bg-white p-2 border border-slate-50 rounded-xl shadow-inner">
                        <div id="qrCodeImage"></div>
                    </div>

                    <div class="hidden md:block h-32 border-l-2 border-dashed border-slate-200"></div>

                    <div class="text-center md:text-left flex-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Valor Total</span>
                        <h2 id="modalAmount" class="text-3xl font-black text-slate-900 tracking-tighter">R$ 0,00</h2>

                        <div class="mt-4 flex items-center gap-2 justify-center md:justify-start">
                            <span class="material-symbols-outlined text-amber-500 !text-sm">timer</span>
                            <span id="timer" class="text-xs font-mono font-bold text-slate-700">15:00</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Código Copia e Cola</p>
                    <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100 flex items-center gap-3">
                        <div class="flex-1 overflow-hidden">
                            <code id="pixCode" class="text-[11px] text-slate-500 truncate block font-mono"></code>
                        </div>
                        <button onclick="copyPixCode()" id="copyButton"
                            class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-elm-600 transition-all active:scale-95">
                            COPIAR
                        </button>
                    </div>
                </div>
            </div>

            <button onclick="closePixModal()"
                class="w-full py-4 bg-slate-50 text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-600 border-t border-slate-100">
                Fechar Bilhete
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .deposit-amount-btn.active {
            background-color: #10b981 !important;
            /* Elm-500 */
            color: white !important;
            border-color: #10b981 !important;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        let timerInterval;

        function getAmount(_this, amount) {
            const buttons = document.querySelectorAll('.deposit-amount-btn');
            buttons.forEach(button => {
                button.classList.remove('active');
            });

            _this.classList.add('active');
            document.querySelector('input[name="amount"]').value = amount;
        }

        function generateTrxId(prefix = 'TRX') {
            const timestamp = Date.now().toString(36);
            const random = Math.random().toString(36).substr(2, 6).toUpperCase();
            return `${prefix}-${timestamp}-${random}`;
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(amount);
        }

        function closePixModal() {
            document.getElementById('pixModal').classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Parar timer
            if (timerInterval) {
                clearInterval(timerInterval);
            }
        }

        function startTimer(seconds) {
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            const timerElement = document.getElementById('timer');

            timerInterval = setInterval(() => {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;

                timerElement.textContent =
                    `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;

                if (seconds <= 0) {
                    clearInterval(timerInterval);
                    timerElement.textContent = "Expirado";
                    timerElement.classList.add('text-red-600');
                }

                seconds--;
            }, 1000);
        }

        async function payment() {
            const amountInput = document.querySelector('input[name="amount"]');
            const amount = amountInput.value;
            const minValue = parseFloat("{{ $minDeposit }}");

            // Referências dos elementos do botão
            const btn = document.getElementById('btnConfirmarDeposito');
            const btnText = document.getElementById('btnText');
            const loaderIcon = document.getElementById('loaderIcon');

            if (!amount || amount < minValue) {
                return alert(`Valor mínimo para depósito: R$ ${minValue.toFixed(2).replace('.', ',')}`);
            }

            // --- INÍCIO DO ESTADO DE LOADING ---
            btn.disabled = true; // Desativa o clique
            btnText.classList.add('hidden'); // Esconde o texto
            loaderIcon.classList.remove('hidden'); // Mostra o loader de rotação

            try {
                const response = await fetch("{{ route('api.deposit.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer {{ $token }}`,
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        amount: amount,
                        transaction_id: `TRX-${Date.now()}`
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    showPixModal(result.data);
                } else {
                    alert(result.message || 'Erro ao gerar pagamento.');
                }
            } catch (error) {
                console.error("Erro:", error);
                alert('Falha na comunicação com o servidor.');
            } finally {
                // --- FIM DO ESTADO DE LOADING ---
                btn.disabled = false; // Reativa o botão
                btnText.classList.remove('hidden'); // Mostra o texto novamente
                loaderIcon.classList.add('hidden'); // Esconde o loader
            }
        }

        function showPixModal(data) {
            // 1. Preencher Valor
            document.getElementById('modalAmount').textContent = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(data.amount);

            // 2. Preencher Código Copia e Cola
            document.getElementById('pixCode').textContent = data.paymentCode;

            // 3. Limpar e Gerar QR Code
            const qrCodeDiv = document.getElementById("qrCodeImage");
            qrCodeDiv.innerHTML = ''; // Limpa QR Code anterior

            QRCode.toCanvas(data.paymentCode, {
                width: 160,
                margin: 0,
                color: {
                    dark: '#0f172a', // Cor do slate-900
                    light: '#ffffff'
                }
            }, function(err, canvas) {
                if (err) console.error(err);
                qrCodeDiv.appendChild(canvas);
                canvas.style.borderRadius = '8px';
            });

            // 4. Abrir Modal e Iniciar Timer
            document.getElementById('pixModal').classList.remove('hidden');
            startTimer(15 * 60);
        }

        // Função de Copiar
        async function copyPixCode() {
            const code = document.getElementById('pixCode').textContent;
            const btn = document.getElementById('copyButton');

            try {
                await navigator.clipboard.writeText(code);
                const originalText = btn.innerText;
                btn.innerText = 'COPIADO!';
                btn.classList.replace('bg-slate-900', 'bg-elm-600');

                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.replace('bg-elm-600', 'bg-slate-900');
                }, 2000);
            } catch (err) {
                alert('Erro ao copiar código.');
            }
        }

        // Fechar modal com ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePixModal();
            }
        });
    </script>
    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
@endpush
