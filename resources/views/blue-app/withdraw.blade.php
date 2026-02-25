@extends('layouts.blueapp')

@section('content')
    <div x-data="withdrawApp()" class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[34px] bg-gradient-to-b from-[#CFE7FF] to-[#EEF4F9] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}"
                    class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                </a>
                <h1 class="text-lg font-bold text-slate-900 uppercase tracking-widest">Solicitar Saque</h1>
                <a href="{{ route('history') }}"
                    class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-slate-600">history</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Disponível</p>
                <h2 class="text-3xl font-black text-[#2C95EF]">R$ {{ number_format($user->balance, 2, ',', '.') }}</h2>
            </div>
        </div>

        <div class="px-5 mt-6 space-y-6">
            {{-- PIX Account Card --}}
            <div class="bg-white rounded-[32px] p-6 shadow-xl shadow-slate-200/50 border border-slate-50">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Minha Conta PIX</h3>
                    <button @click="editingPix = !editingPix" class="text-[10px] font-bold text-[#2C95EF] uppercase">
                        <span x-text="hasPix && !editingPix ? 'Alterar Conta' : 'Cancelar'"></span>
                    </button>
                </div>

                {{-- Display PIX Info --}}
                <template x-if="hasPix && !editingPix">
                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div
                            class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#2C95EF] shadow-sm">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-[10px] text-slate-400 font-bold uppercase truncate" x-text="pixName"></p>
                            <p class="text-sm font-bold text-slate-900 truncate" x-text="pixKey"></p>
                            <span
                                class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-[#EAF4FF] text-[#2C95EF] uppercase mt-1 inline-block"
                                x-text="pixType"></span>
                        </div>
                    </div>
                </template>

                {{-- Edit PIX Info --}}
                <div x-show="!hasPix || editingPix" x-cloak class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Nome Completo do
                            Titular</label>
                        <input type="text" x-model="pixForm.realname"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-[#2C95EF] transition-all"
                            placeholder="Como consta no banco">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Tipo de
                            Chave</label>
                        <select x-model="pixForm.pix_type"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-[#2C95EF] transition-all">
                            <option value="CPF">CPF</option>
                            <option value="EMAIL">E-mail</option>
                            <option value="PHONE">Telefone</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Chave PIX</label>
                        <input type="text" x-model="pixForm.pix_key"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-[#2C95EF] transition-all"
                            :placeholder="pixForm.pix_type === 'CPF' ? '000.000.000-00' : (pixForm.pix_type === 'PHONE' ?
                                '(00) 00000-0000' : 'seu@email.com')">
                    </div>
                    <button @click="savePix()" :disabled="savingPix"
                        class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl active:scale-[0.98] transition-all disabled:opacity-50">
                        <span x-text="savingPix ? 'Salvando...' : 'Salvar Conta PIX'"></span>
                    </button>
                </div>
            </div>

            {{-- Withdraw Form --}}
            <div class="bg-white rounded-[32px] p-6 shadow-xl shadow-slate-200/50 border border-slate-50"
                x-show="hasPix && !editingPix">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Quanto
                    deseja sacar?</label>

                <div class="relative flex items-center justify-center mb-8">
                    <span class="text-2xl font-black text-[#2C95EF] mr-2">R$</span>
                    <input type="number" x-model="withdrawAmount"
                        class="w-full max-w-[200px] text-4xl font-black text-slate-900 outline-none text-center bg-transparent placeholder:text-slate-100"
                        placeholder="0,00">
                </div>

                <div class="grid grid-cols-4 gap-2 mb-8">
                    @foreach ([50, 100, 200, 500] as $val)
                        <button @click="withdrawAmount = {{ $val }}"
                            :class="withdrawAmount == {{ $val }} ? 'bg-[#2C95EF] text-white' :
                                'bg-slate-50 text-slate-600'"
                            class="py-3 text-xs font-bold rounded-xl transition-all active:scale-90">
                            {{ $val }}
                        </button>
                    @endforeach
                </div>

                <div class="bg-blue-50 rounded-2xl p-4 mb-8 space-y-2">
                    <div class="flex justify-between text-[10px] font-bold">
                        <span class="text-slate-400 uppercase">Taxa de Saque ({{ setting('withdraw_charge') }}%)</span>
                        <span class="text-slate-600">R$ <span
                                x-text="formatMoney(withdrawAmount * {{ (float) setting('withdraw_charge') }} / 100)"></span></span>
                    </div>
                    <div class="flex justify-between text-xs font-black pt-2 border-t border-blue-100">
                        <span class="text-[#2C95EF] uppercase">Você receberá</span>
                        <span class="text-slate-900 font-black text-sm">R$ <span
                                x-text="formatMoney(withdrawAmount - (withdrawAmount * {{ (float) setting('withdraw_charge') }} / 100))"></span></span>
                    </div>
                </div>

                <button @click="requestWithdraw()"
                    :disabled="requesting || withdrawAmount < {{ (float) setting('minimum_withdraw') }}"
                    class="w-full bg-[#2C95EF] hover:bg-slate-900 active:scale-95 text-white font-bold py-5 px-6 rounded-[24px] shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-3 disabled:opacity-50">
                    <template x-if="requesting">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </template>
                    <span x-text="requesting ? 'Processando...' : 'Confirmar Saque'"></span>
                </button>

                <p class="mt-4 text-[9px] text-center text-slate-400 font-bold uppercase tracking-widest">
                    Mínimo: R$ {{ number_format(setting('minimum_withdraw'), 2, ',', '.') }} | Máximo: R$
                    {{ number_format(setting('maximum_withdraw'), 2, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush

    @push('scripts')
        <script>
            function withdrawApp() {
                return {
                    hasPix: {{ $user->pix_key ? 'true' : 'false' }},
                    editingPix: false,
                    savingPix: false,
                    requesting: false,
                    pixName: '{{ $user->realname }}',
                    pixKey: '{{ $user->pix_key }}',
                    pixType: '{{ $user->pix_type }}',
                    withdrawAmount: null,
                    pixForm: {
                        realname: '{{ $user->realname }}',
                        pix_type: '{{ $user->pix_type ?: 'CPF' }}',
                        pix_key: '{{ $user->pix_key }}'
                    },
                    formatMoney(value) {
                        return new Intl.NumberFormat('pt-BR', {
                            minimumFractionDigits: 2
                        }).format(value);
                    },
                    async savePix() {
                        this.savingPix = true;
                        try {
                            const response = await fetch("{{ route('user.update.pix') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(this.pixForm)
                            });
                            const result = await response.json();
                            if (response.ok) {
                                this.pixName = this.pixForm.realname;
                                this.pixKey = this.pixForm.pix_key;
                                this.pixType = this.pixForm.pix_type;
                                this.hasPix = true;
                                this.editingPix = false;
                                alert(result.message);
                            } else {
                                alert(result.message || 'Erro ao salvar conta PIX');
                            }
                        } catch (error) {
                            alert('Erro de conexão');
                        } finally {
                            this.savingPix = false;
                        }
                    },
                    async requestWithdraw() {
                        if (this.withdrawAmount < {{ (float) setting('minimum_withdraw') }}) return;

                        this.requesting = true;
                        try {
                            const response = await fetch("{{ route('user.withdraw.request') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    amount: this.withdrawAmount
                                })
                            });
                            const result = await response.json();
                            if (response.ok && result.success) {
                                alert(result.message);
                                window.location.reload();
                            } else {
                                alert(result.message || 'Erro ao solicitar saque');
                            }
                        } catch (error) {
                            alert('Erro de conexão');
                        } finally {
                            this.requesting = false;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection
