@extends('layouts.dmk')

@section('content')
    <div class="flex mb-20 min-h-screen w-full items-center justify-center bg-elm-50 max-md:px-4 py-10">
        <div
            class="w-full max-w-[720px] overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 hover:shadow-elm-200/50">

            <div class="h-2 bg-elm-600 w-full"></div>

            <div class="p-8 md:p-10">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-3xl font-extrabold text-elm-900 tracking-tight">
                            Saque PIX
                        </h1>
                        <a href="{{ route('history') }}"
                            class="flex items-center space-x-2 text-elm-600 hover:text-elm-700 transition-colors group">
                            <span class="font-medium text-sm">Histórico</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </a>
                    </div>

                    <!-- Saldo Card -->
                    <div class="bg-gradient-to-r from-elm-600 to-elm-700 rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-elm-100 text-sm font-medium mb-1">Saldo Disponível</p>
                                <p class="text-white text-3xl font-bold">{{ price(user()->balance) }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form id="withdrawForm" class="space-y-6">
                    @csrf

                    <!-- Tipo de Chave PIX -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">
                            Tipo de Chave PIX
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['CPF', 'EMAIL', 'PHONE', 'RANDOM'] as $type)
                                <button type="button"
                                    class="pix-type-btn flex flex-col items-center justify-center p-4 rounded-xl border-2 border-elm-100 bg-elm-50/30 text-elm-700 hover:border-elm-500 hover:bg-elm-50 transition-all duration-300"
                                    data-type="{{ $type }}">
                                    <span class="text-sm font-bold">
                                        {{ $type == 'PHONE' ? 'Telefone' : ($type == 'RANDOM' ? 'Aleatória' : $type) }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" id="pixType" name="pixType" required>
                    </div>

                    <!-- Chave PIX -->
                    <div class="space-y-1.5">
                        <label for="pixKey" class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">
                            Chave PIX
                        </label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-400 group-focus-within:text-elm-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </span>
                            <input type="text" id="pixKey" name="pixKey"
                                class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                placeholder="Selecione o tipo primeiro" required>
                        </div>
                        <div class="text-red-600 text-sm mt-2 hidden error-msg" id="pixKey-error"></div>
                    </div>

                    <!-- Nome e CPF -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="name" class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">
                                Nome Completo
                            </label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-400 group-focus-within:text-elm-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <input type="text" id="name" name="name"
                                    class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                    placeholder="Nome do titular" required>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="document" class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">
                                CPF do Titular
                            </label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-400 group-focus-within:text-elm-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </span>
                                <input type="text" id="document" name="document"
                                    class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                    placeholder="000.000.000-00" required>
                            </div>
                        </div>
                    </div>

                    <!-- Valor do Saque -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">
                            Valor do Saque
                        </label>

                        <!-- Botões de valores rápidos -->
                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['50.00', '100.00', '200.00'] as $val)
                                <button type="button" onclick="setQuickAmount(this, '{{ $val }}')"
                                    class="quick-amount-btn py-3 rounded-xl border-2 border-elm-100 bg-elm-50/30 text-elm-700 hover:border-elm-500 hover:bg-elm-50 transition-all font-bold text-sm">
                                    R$ {{ number_format($val, 2, ',', '.') }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Input de valor -->
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-600 font-bold text-lg">
                                R$
                            </span>
                            <input type="text" id="amount" name="amount"
                                class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-12 pr-4 text-elm-900 text-lg font-bold placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0"
                                placeholder="0,00" required>
                        </div>

                        <!-- Info de taxa -->
                        <div class="flex items-center justify-between px-1 text-sm">
                            <span class="text-elm-600">Taxa: {{ setting('withdraw_charge', 10) }}%</span>
                            <span class="text-elm-700 font-bold" id="afterFeeAmount">Você receberá: R$ 0,00</span>
                        </div>
                    </div>

                    <!-- Aviso -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-blue-800 text-sm leading-relaxed">
                            O prazo de processamento é de 1 a 30 minutos dentro do horário comercial.
                        </p>
                    </div>

                    <!-- Botão de Submit -->
                    <button type="submit" id="submitButton"
                        class="group relative flex w-full items-center justify-center overflow-hidden rounded-xl bg-elm-600 px-8 py-4 text-white shadow-lg transition-all hover:bg-elm-700 hover:shadow-elm-200 active:scale-[0.98]">
                        <span class="relative z-10 font-bold tracking-wide flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>CONFIRMAR SAQUE PIX</span>
                        </span>
                        <div
                            class="absolute inset-0 z-0 bg-gradient-to-r from-elm-500 to-elm-700 opacity-0 transition-opacity group-hover:opacity-100">
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50 hidden">
        <div class="bg-white p-8 rounded-2xl flex flex-col items-center shadow-2xl">
            <div class="w-12 h-12 border-4 border-elm-600 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-elm-900 font-bold">Processando solicitação...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedPixType = '';
        const userBalance = {{ (float) user()->balance }};
        const feePercent = {{ (float) setting('withdraw_charge', 10) }};

        // Seleção de Tipo de PIX
        document.querySelectorAll('.pix-type-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.pix-type-btn').forEach(b => {
                    b.classList.remove('border-elm-600', 'bg-elm-600', 'text-white');
                    b.classList.add('border-elm-100', 'bg-elm-50/30', 'text-elm-700');
                });

                this.classList.remove('border-elm-100', 'bg-elm-50/30', 'text-elm-700');
                this.classList.add('border-elm-600', 'bg-elm-600', 'text-white');

                selectedPixType = this.dataset.type;
                document.getElementById('pixType').value = selectedPixType;

                const placeholders = {
                    'CPF': '000.000.000-00',
                    'EMAIL': 'seu@email.com',
                    'PHONE': '(00) 00000-0000',
                    'RANDOM': 'Chave aleatória'
                };
                document.getElementById('pixKey').placeholder = placeholders[selectedPixType];
            });
        });

        function setQuickAmount(btn, val) {
            // Remove seleção de todos os botões
            document.querySelectorAll('.quick-amount-btn').forEach(b => {
                b.classList.remove('border-elm-600', 'bg-elm-600', 'text-white');
                b.classList.add('border-elm-100', 'bg-elm-50/30', 'text-elm-700');
            });

            // Adiciona seleção ao botão clicado
            btn.classList.remove('border-elm-100', 'bg-elm-50/30', 'text-elm-700');
            btn.classList.add('border-elm-600', 'bg-elm-600', 'text-white');

            const input = document.getElementById('amount');
            input.value = val.replace('.', ',');
            calculateFee();
        }

        function calculateFee() {
            const valStr = document.getElementById('amount').value.replace(',', '.');
            const val = parseFloat(valStr) || 0;
            const afterFee = val - (val * (feePercent / 100));
            document.getElementById('afterFeeAmount').textContent =
                `Você receberá: R$ ${afterFee.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        }

        document.getElementById('amount').addEventListener('input', function() {
            // Remove seleção dos botões de valor rápido
            document.querySelectorAll('.quick-amount-btn').forEach(b => {
                b.classList.remove('border-elm-600', 'bg-elm-600', 'text-white');
                b.classList.add('border-elm-100', 'bg-elm-50/30', 'text-elm-700');
            });
            calculateFee();
        });

        // Formatação CPF
        document.getElementById('document').addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 11);
            if (v.length > 9) v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{3})/, "$1.$2.$3");
            else if (v.length > 3) v = v.replace(/(\d{3})(\d{3})/, "$1.$2");
            e.target.value = v;
        });

        // Submissão AJAX
        document.getElementById('withdrawForm').onsubmit = async (e) => {
            e.preventDefault();
            const loader = document.getElementById('loader');
            const btn = document.getElementById('submitButton');

            if (!selectedPixType) return Swal.fire('Erro', 'Selecione o tipo de chave PIX', 'error');

            loader.classList.remove('hidden');
            btn.disabled = true;

            try {
                const formData = new FormData(e.target);
                let amountVal = formData.get('amount').replace(',', '.');
                formData.set('amount', amountVal);
                const response = await fetch("{{ route('api.withdraw.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'accept': 'application/json'
                    }
                });

                const data = await response.json();
                loader.classList.add('hidden');

                if (data.success) {
                    Swal.fire('Sucesso!', data.message || 'Saque solicitado com sucesso.', 'success')
                        .then(() => window.location.reload());
                } else {
                    Swal.fire('Ops!', data.message || 'Erro ao processar saque.', 'error');
                    btn.disabled = false;
                }
            } catch (error) {
                loader.classList.add('hidden');
                btn.disabled = false;
                Swal.fire('Erro', 'Falha de comunicação com o servidor.', 'error');
            }
        };
    </script>
@endpush
