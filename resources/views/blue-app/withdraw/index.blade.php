@extends('layouts.blueapp')

@section('content')
    <div x-data="withdrawApp()" class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Coletar <span class="text-pink-500">Saldo</span></h1>
                <a href="{{ route('history') }}" class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">receipt_long</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="text-[11px] font-bold text-pink-400 uppercase tracking-[0.2em] mb-1">Disponível para Saque</p>
                <h2 class="text-3xl font-black text-slate-800">R$ {{ number_format(user()->balance, 2, ',', '.') }}</h2>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-5 -mt-6">
            <div class="bg-white rounded-[40px] p-8 shadow-xl shadow-black/5 border border-pink-50">
                <form id="withdrawForm" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Chave PIX ({{ user()->pix_type }})</label>
                        <div class="bg-pink-50/30 p-4 rounded-2xl border border-pink-100 flex items-center gap-3">
                            <span class="text-xl">🔑</span>
                            <span class="text-sm font-bold text-slate-700">{{ user()->pix_key }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Quanto deseja retirar?</label>
                        <div class="relative flex items-center justify-center mb-6">
                            <span class="text-2xl font-black text-pink-500 mr-2">R$</span>
                            <input type="number" name="amount" x-model="amount" @input="calculateFee()"
                                class="w-full max-w-[180px] text-4xl font-black text-slate-800 outline-none text-center bg-transparent placeholder:text-slate-100"
                                placeholder="0,00">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach ([50, 100, 200] as $val)
                                <button type="button" @click="amount = {{ $val }}; calculateFee()"
                                    :class="amount == {{ $val }} ? 'bg-pink-500 text-white shadow-lg' : 'bg-pink-50/50 text-pink-400 border border-pink-100'"
                                    class="py-3 text-xs font-bold rounded-2xl transition-all">
                                    {{ $val }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-[#F0F7FF] p-4 rounded-[24px] border border-blue-100">
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-wider mb-2">
                            <span class="text-slate-400">Taxa ({{ setting('withdraw_charge', 10) }}%)</span>
                            <span class="text-pink-400">- R$ <span x-text="feeAmount">0,00</span></span>
                        </div>
                        <div class="flex justify-between text-xs font-black uppercase tracking-widest pt-2 border-t border-blue-200">
                            <span class="text-slate-800">Você Recebe</span>
                            <span class="text-emerald-500 text-lg">R$ <span x-text="finalAmount">0,00</span></span>
                        </div>
                    </div>

                    <button type="submit" :disabled="loading || !amount || amount < 5"
                        class="w-full bg-slate-800 hover:bg-pink-500 active:scale-[0.98] text-white font-bold py-5 px-6 rounded-[28px] shadow-xl transition-all flex items-center justify-center gap-3 disabled:opacity-50">
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="loading ? 'Processando...' : 'Confirmar Saque'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function withdrawApp() {
            return {
                amount: null,
                loading: false,
                feeAmount: '0,00',
                finalAmount: '0,00',
                feePercent: {{ (float) setting('withdraw_charge', 10) }},

                calculateFee() {
                    const val = parseFloat(this.amount) || 0;
                    const fee = val * (this.feePercent / 100);
                    const final = val - fee;
                    this.feeAmount = fee.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    this.finalAmount = final.toLocaleString('pt-BR', {minimumFractionDigits: 2});
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
                            if (data.success) {
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#FF80A6',
                                    confirmButtonText: 'Entendido'
                                }).then(() => window.location.reload());
                            } else {
                                Swal.fire('Erro', data.message || 'Erro ao processar saque', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Erro', 'Falha na conexão', 'error');
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
