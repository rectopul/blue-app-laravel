@extends('layouts.blueapp')

@section('content')
    <div x-data="depositApp()" class="pb-32">
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-[20px] border border-pink-50 bg-white shadow-sm transition-all active:scale-90">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold tracking-tight text-slate-800">Depositar <span class="text-pink-500">Saldo</span></h1>
                <a href="{{ route('history') }}" class="grid h-12 w-12 place-items-center rounded-[20px] border border-pink-50 bg-white shadow-sm transition-all active:scale-90">
                    <span class="material-symbols-outlined text-pink-400">receipt_long</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.2em] text-pink-400">Saldo atual</p>
                <h2 class="text-3xl font-black text-slate-800">R$ {{ number_format($balance, 2, ',', '.') }}</h2>
            </div>
        </div>

        <div class="-mt-6 px-5">
            <div class="rounded-[40px] border border-pink-50 bg-white p-7 shadow-xl shadow-black/5">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-[24px] bg-[#F8FBFF] p-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Metodo</p>
                        <div class="mt-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                            <span class="material-symbols-outlined text-[#2C95EF]">qr_code_2</span>
                            PIX automatico
                        </div>
                    </div>
                    <div class="rounded-[24px] bg-[#FFF7FA] p-4 text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Minimo</p>
                        <p class="mt-2 text-sm font-bold text-slate-700">R$ {{ number_format($minDeposit, 2, ',', '.') }}</p>
                    </div>
                </div>

                <label class="mt-8 block text-center text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Defina o valor do deposito</label>

                <div class="relative mt-6 flex items-center justify-center">
                    <span class="mr-2 text-3xl font-black text-pink-500">R$</span>
                    <input type="number" x-model="amount"
                        class="w-full max-w-[220px] bg-transparent text-center text-5xl font-black text-slate-800 outline-none placeholder:text-slate-200"
                        placeholder="0,00">
                </div>

                <div class="mt-8 grid grid-cols-3 gap-3">
                    @foreach ([50, 100, 200, 500, 1000, 2000] as $val)
                        <button type="button" @click="amount = {{ $val }}"
                            :class="amount == {{ $val }} ? 'bg-pink-500 text-white shadow-xl shadow-pink-200 scale-105' : 'border border-pink-100 bg-pink-50/50 text-pink-400'"
                            class="smooth-transition rounded-[22px] py-4 text-sm font-bold transition-all active:scale-90">
                            {{ $val }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-8 rounded-[28px] border border-slate-100 bg-slate-50 p-5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold uppercase tracking-wider text-slate-400">Entrada selecionada</span>
                        <span class="font-bold text-slate-700">R$ <span x-text="formatMoney(amount || 0)"></span></span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="font-bold uppercase tracking-wider text-slate-400">Credito esperado</span>
                        <span class="font-bold text-emerald-500">R$ <span x-text="formatMoney(amount || 0)"></span></span>
                    </div>
                </div>

                <button @click="generatePix()" :disabled="loading || amount < {{ $minDeposit }}"
                    class="mt-8 flex w-full items-center justify-center gap-3 rounded-[28px] bg-slate-800 px-6 py-5 font-bold text-white shadow-xl shadow-black/10 transition-all hover:bg-pink-500 active:scale-[0.98] disabled:opacity-50">
                    <template x-if="loading">
                        <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <span class="material-symbols-outlined" x-show="!loading">payments</span>
                    <span x-text="loading ? 'Gerando PIX...' : 'Gerar deposito PIX'"></span>
                </button>
            </div>

            <div class="mt-8 space-y-4 px-1">
                <h3 class="mb-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Como funciona</h3>

                <div class="flex items-start gap-4 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-pink-50 text-pink-500">
                        <span class="material-symbols-outlined">looks_one</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Escolha o valor</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">Use os atalhos ou digite manualmente quanto deseja adicionar.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-[#2C95EF]">
                        <span class="material-symbols-outlined">qr_code_scanner</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Pague o QR Code</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">O sistema gera um PIX copia e cola e o QR para pagamento imediato.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500">
                        <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Credito automatico</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">Quando o gateway confirmar o pagamento, o valor entra na carteira automaticamente.</p>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="pixModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="closePixModal()"></div>

            <div class="relative w-full max-w-sm overflow-hidden rounded-[40px] bg-white shadow-2xl">
                <div class="bg-slate-900 p-8 text-center text-white">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#2C95EF]">
                        <span class="material-symbols-outlined">qr_code_2</span>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-widest">Pagamento PIX</h3>
                    <p class="mt-1 text-xs text-slate-400">Codigo valido por 15 minutos</p>
                </div>

                <div class="p-8">
                    <div class="flex flex-col items-center">
                        <div class="mb-6 rounded-3xl border-2 border-dashed border-slate-100 bg-white p-3 shadow-inner">
                            <div id="qrCodeImage"></div>
                        </div>

                        <div class="mb-8 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Valor do deposito</span>
                            <h2 class="text-3xl font-black text-slate-900">R$ <span x-text="formatMoney(amount)"></span></h2>
                            <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-amber-600">
                                <span class="material-symbols-outlined !text-sm">timer</span>
                                <span class="font-mono text-[10px] font-bold" x-text="timerText">15:00</span>
                            </div>
                        </div>

                        <div class="w-full">
                            <p class="mb-3 ml-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">Copia e cola</p>
                            <div class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="flex-1 overflow-hidden">
                                    <code class="block truncate font-mono text-[10px] text-slate-500" x-text="pixCode"></code>
                                </div>
                                <button @click="copyCode()" class="rounded-xl bg-slate-900 px-4 py-2.5 text-[10px] font-bold text-white transition-all active:scale-95" x-text="copyBtnText"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <button @click="closePixModal()" class="w-full border-t border-slate-100 bg-slate-50 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400 transition-colors hover:text-slate-600">
                    Fechar janela
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        function depositApp() {
            return {
                amount: null,
                loading: false,
                pixModal: false,
                pixCode: '',
                timerText: '15:00',
                timerInterval: null,
                copyBtnText: 'COPIAR',
                formatMoney(value) {
                    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value || 0));
                },
                async generatePix() {
                    if (this.amount < {{ $minDeposit }}) {
                        alert('O valor minimo para deposito nao foi atingido.');
                        return;
                    }

                    this.loading = true;
                    try {
                        const response = await fetch("{{ route('api.deposit.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer {{ $token }}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ amount: this.amount })
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            throw new Error(result.message || 'Erro ao gerar PIX.');
                        }

                        this.showPix(result.data);
                    } catch (error) {
                        alert(error.message || 'Erro de conexao.');
                    } finally {
                        this.loading = false;
                    }
                },
                showPix(data) {
                    this.pixCode = data.paymentCode;
                    this.pixModal = true;

                    setTimeout(() => {
                        const qrCodeDiv = document.getElementById('qrCodeImage');
                        qrCodeDiv.innerHTML = '';
                        QRCode.toCanvas(this.pixCode, {
                            width: 180,
                            margin: 0,
                            color: { dark: '#0f172a', light: '#ffffff' }
                        }, (err, canvas) => {
                            if (!err) {
                                qrCodeDiv.appendChild(canvas);
                                canvas.style.borderRadius = '16px';
                            }
                        });
                    }, 100);

                    this.startTimer(15 * 60);
                },
                startTimer(seconds) {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        const mins = Math.floor(seconds / 60);
                        const secs = seconds % 60;
                        this.timerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        if (seconds <= 0) {
                            clearInterval(this.timerInterval);
                            this.timerText = 'EXPIRADO';
                        }
                        seconds--;
                    }, 1000);
                },
                closePixModal() {
                    this.pixModal = false;
                    if (this.timerInterval) clearInterval(this.timerInterval);
                },
                async copyCode() {
                    try {
                        await navigator.clipboard.writeText(this.pixCode);
                        this.copyBtnText = 'COPIADO';
                        setTimeout(() => this.copyBtnText = 'COPIAR', 2000);
                    } catch (err) {
                        alert('Nao foi possivel copiar o codigo.');
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
