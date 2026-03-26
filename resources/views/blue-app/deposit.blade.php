@extends('layouts.blueapp')

@section('content')
    <div x-data="depositApp()" class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Recarga <span class="text-pink-500">Mágica</span></h1>
                <a href="{{ route('history') }}" class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">receipt_long</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="text-[11px] font-bold text-pink-400 uppercase tracking-[0.2em] mb-1">Saldo em Carteira</p>
                <h2 class="text-3xl font-black text-slate-800">R$ {{ number_format($balance, 2, ',', '.') }}</h2>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-5 -mt-6">
            <div class="bg-white rounded-[40px] p-8 shadow-xl shadow-black/5 border border-pink-50">
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-8 text-center">Defina o valor do depósito</label>

                <div class="relative flex items-center justify-center mb-10">
                    <span class="text-3xl font-black text-pink-500 mr-2">R$</span>
                    <input type="number" x-model="amount"
                        class="w-full max-w-[200px] text-5xl font-black text-slate-800 outline-none text-center bg-transparent placeholder:text-slate-100"
                        placeholder="0,00">
                </div>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    @foreach ([50, 100, 200, 500, 1000, 2000] as $val)
                        <button @click="amount = {{ $val }}"
                            :class="amount == {{ $val }} ? 'bg-pink-500 text-white shadow-xl shadow-pink-200 ring-transparent scale-105' : 'bg-pink-50/50 text-pink-400 border border-pink-100'"
                            class="py-4 text-sm font-bold rounded-[22px] transition-all active:scale-90 smooth-transition">
                            {{ $val }}
                        </button>
                    @endforeach
                </div>

                <button @click="generatePix()" :disabled="loading || amount < {{ $minDeposit }}"
                    class="w-full bg-slate-800 hover:bg-pink-500 active:scale-[0.98] text-white font-bold py-5 px-6 rounded-[28px] shadow-xl shadow-black/10 transition-all flex items-center justify-center gap-3 disabled:opacity-50">
                    <template x-if="loading">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <span x-text="loading ? 'Gerando PIX...' : 'Confirmar Depósito'"></span>
                </button>

                <p class="mt-4 text-[10px] text-center text-slate-400 font-medium">Depósito mínimo: R$ {{ number_format($minDeposit, 2, ',', '.') }}</p>
            </div>

            {{-- Instruções --}}
            <div class="mt-10 space-y-4 px-2">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Passo a Passo</h3>

                <div class="flex items-center gap-5 bg-white/80 p-5 rounded-[30px] border border-pink-50 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center shrink-0 border border-pink-100">
                        <span class="text-xl font-black">1</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-500 leading-relaxed uppercase tracking-wider">Escolha o valor e gere o código PIX.</p>
                </div>

                <div class="flex items-center gap-4 bg-white/50 p-4 rounded-[22px] border border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-[#2C95EF] flex items-center justify-center shrink-0">
                        <span class="text-lg font-bold">2</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Pague o PIX em seu banco de preferência.</p>
                </div>

                <div class="flex items-center gap-4 bg-white/50 p-4 rounded-[22px] border border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-[#2C95EF] flex items-center justify-center shrink-0">
                        <span class="text-lg font-bold">3</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">O saldo será creditado automaticamente após a compensação.</p>
                </div>
            </div>
        </div>

        {{-- PIX Modal --}}
        <div x-show="pixModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="closePixModal()"></div>

            <div class="relative w-full max-w-sm bg-white rounded-[40px] overflow-hidden shadow-2xl animate-in zoom-in duration-300">
                <div class="bg-slate-900 p-8 text-white text-center">
                    <div class="mx-auto w-12 h-12 bg-[#2C95EF] rounded-2xl flex items-center justify-center mb-4 rotate-12">
                        <span class="material-symbols-outlined">qr_code_2</span>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-widest">Pagamento PIX</h3>
                    <p class="text-slate-400 text-xs mt-1">Válido por 15 minutos</p>
                </div>

                <div class="p-8 bg-white relative">
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-3 border-2 border-dashed border-slate-100 rounded-3xl mb-6 shadow-inner">
                            <div id="qrCodeImage"></div>
                        </div>

                        <div class="text-center mb-8">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Valor do Depósito</span>
                            <h2 class="text-3xl font-black text-slate-900">R$ <span x-text="formatMoney(amount)"></span></h2>

                            <div class="mt-3 inline-flex items-center gap-2 bg-amber-50 px-3 py-1.5 rounded-full text-amber-600">
                                <span class="material-symbols-outlined !text-sm">timer</span>
                                <span class="text-[10px] font-bold font-mono" x-text="timerText">15:00</span>
                            </div>
                        </div>

                        <div class="w-full">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-3 ml-1 tracking-widest">Código Copia e Cola</p>
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center gap-3 group">
                                <div class="flex-1 overflow-hidden">
                                    <code class="text-[10px] text-slate-500 truncate block font-mono" x-text="pixCode"></code>
                                </div>
                                <button @click="copyCode()" class="bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[10px] font-bold active:scale-95 transition-all" x-text="copyBtnText">
                                    COPIAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button @click="closePixModal()" class="w-full py-5 bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-slate-600 border-t border-slate-100 transition-colors">
                    Fechar Janela
                </button>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush

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
                    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(value);
                },
                async generatePix() {
                    if (this.amount < {{ $minDeposit }}) return;

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
                            body: JSON.stringify({
                                amount: this.amount
                            })
                        });

                        const result = await response.json();
                        if (response.ok) {
                            this.showPix(result.data);
                        } else {
                            alert(result.message || 'Erro ao gerar PIX');
                        }
                    } catch (error) {
                        alert('Erro de conexão');
                    } finally {
                        this.loading = false;
                    }
                },
                showPix(data) {
                    this.pixCode = data.paymentCode;
                    this.pixModal = true;

                    // Gerar QR Code
                    setTimeout(() => {
                        const qrCodeDiv = document.getElementById("qrCodeImage");
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
                        this.copyBtnText = 'COPIADO!';
                        setTimeout(() => this.copyBtnText = 'COPIAR', 2000);
                    } catch (err) {
                        alert('Erro ao copiar');
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
