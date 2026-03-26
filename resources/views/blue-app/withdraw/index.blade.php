@extends('layouts.blueapp')

@section('content')
    <div x-data="withdrawApp()" class="pb-32">
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-[20px] border border-pink-50 bg-white shadow-sm transition-all active:scale-90">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold tracking-tight text-slate-800">Solicitar <span class="text-pink-500">Saque</span></h1>
                <a href="{{ route('history') }}" class="grid h-12 w-12 place-items-center rounded-[20px] border border-pink-50 bg-white shadow-sm transition-all active:scale-90">
                    <span class="material-symbols-outlined text-pink-400">receipt_long</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.2em] text-pink-400">Disponivel para saque</p>
                <h2 class="text-3xl font-black text-slate-800">R$ {{ number_format(user()->balance, 2, ',', '.') }}</h2>
            </div>
        </div>

        <div class="-mt-6 px-5">
            <div class="rounded-[40px] border border-pink-50 bg-white p-7 shadow-xl shadow-black/5">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-[24px] bg-[#F8FBFF] p-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Minimo</p>
                        <p class="mt-2 text-sm font-bold text-slate-700">R$ {{ number_format(setting('minimum_withdraw'), 2, ',', '.') }}</p>
                    </div>
                    <div class="rounded-[24px] bg-[#FFF7FA] p-4 text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Taxa</p>
                        <p class="mt-2 text-sm font-bold text-slate-700">{{ number_format((float) setting('withdraw_charge', 0), 2, ',', '.') }}%</p>
                    </div>
                </div>

                <div class="mt-6 rounded-[28px] border border-slate-100 bg-slate-50 p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-pink-50 text-pink-500">
                            <span class="material-symbols-outlined">account_balance</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Carteira PIX</p>
                            <p class="mt-1 text-sm font-bold text-slate-700">{{ user()->pix_type ?: 'Nao configurado' }}</p>
                            <p class="mt-1 break-all text-xs text-slate-500">{{ user()->pix_key ?: 'Cadastre sua chave PIX antes de solicitar o saque.' }}</p>
                        </div>
                    </div>
                </div>

                <form id="withdrawForm" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label class="mb-4 block text-center text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Quanto deseja retirar?</label>
                        <div class="relative flex items-center justify-center">
                            <span class="mr-2 text-3xl font-black text-pink-500">R$</span>
                            <input type="number" name="amount" x-model="amount" @input="calculateFee()"
                                class="w-full max-w-[220px] bg-transparent text-center text-5xl font-black text-slate-800 outline-none placeholder:text-slate-200"
                                placeholder="0,00">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        @foreach ([50, 100, 200] as $val)
                            <button type="button" @click="amount = {{ $val }}; calculateFee()"
                                :class="amount == {{ $val }} ? 'bg-pink-500 text-white shadow-lg shadow-pink-200' : 'border border-pink-100 bg-pink-50/50 text-pink-400'"
                                class="rounded-2xl py-3 text-xs font-bold transition-all">
                                {{ $val }}
                            </button>
                        @endforeach
                    </div>

                    <div class="rounded-[28px] border border-slate-100 bg-[#F8FBFF] p-5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold uppercase tracking-wider text-slate-400">Valor solicitado</span>
                            <span class="font-bold text-slate-700">R$ <span x-text="formatMoney(amount || 0)">0,00</span></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-xs">
                            <span class="font-bold uppercase tracking-wider text-slate-400">Taxa descontada</span>
                            <span class="font-bold text-pink-400">R$ <span x-text="feeAmount">0,00</span></span>
                        </div>
                        <div class="mt-3 flex items-center justify-between border-t border-blue-100 pt-3 text-xs">
                            <span class="font-bold uppercase tracking-wider text-slate-800">Voce recebe</span>
                            <span class="text-lg font-black text-emerald-500">R$ <span x-text="finalAmount">0,00</span></span>
                        </div>
                    </div>

                    <button type="submit" :disabled="loading || !amount || amount < {{ max(5, (int) setting('minimum_withdraw')) }}"
                        class="flex w-full items-center justify-center gap-3 rounded-[28px] bg-slate-800 px-6 py-5 font-bold text-white shadow-xl transition-all hover:bg-pink-500 active:scale-[0.98] disabled:opacity-50">
                        <template x-if="loading">
                            <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span class="material-symbols-outlined" x-show="!loading">outbox</span>
                        <span x-text="loading ? 'Enviando...' : 'Confirmar saque'"></span>
                    </button>
                </form>
            </div>

            <div class="mt-8 space-y-4 px-1">
                <h3 class="mb-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Regras importantes</h3>

                <div class="flex items-start gap-4 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-500">
                        <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Cadastro PIX obrigatorio</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">A conta precisa ter chave PIX, nome real e documento configurados para liberar o saque.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-[#2C95EF]">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Investimento ativo</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">O sistema exige pelo menos um investimento realizado antes de aceitar solicitacoes de saque.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Analise de seguranca</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">Saques podem seguir para analise dependendo do perfil de risco antifraude da conta.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function withdrawApp() {
            return {
                amount: null,
                loading: false,
                feeAmount: '0,00',
                finalAmount: '0,00',
                feePercent: {{ (float) setting('withdraw_charge', 0) }},

                formatMoney(value) {
                    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value || 0));
                },
                calculateFee() {
                    const val = parseFloat(this.amount) || 0;
                    const fee = val * (this.feePercent / 100);
                    const final = Math.max(0, val - fee);
                    this.feeAmount = this.formatMoney(fee);
                    this.finalAmount = this.formatMoney(final);
                },
                async init() {
                    document.getElementById('withdrawForm').onsubmit = async (e) => {
                        e.preventDefault();
                        this.loading = true;

                        try {
                            const formData = new FormData(e.target);
                            const response = await fetch("{{ route('api.withdraw.store') }}", {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();
                            if (!response.ok || !data.success) {
                                throw new Error(data.message || 'Erro ao processar saque.');
                            }

                            alert(data.message);
                            window.location.reload();
                        } catch (error) {
                            alert(error.message || 'Falha na conexao.');
                        } finally {
                            this.loading = false;
                        }
                    };
                }
            }
        }
    </script>
    @endpush
@endsection
