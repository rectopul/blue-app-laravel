@extends('layouts.blueapp')

@section('content')
    <div class="pb-32">
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('profile') }}" class="grid h-12 w-12 place-items-center rounded-[20px] border border-pink-50 bg-white shadow-sm transition-all active:scale-90">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold tracking-tight text-slate-800">Minha <span class="text-pink-500">Carteira</span></h1>
                <div class="w-12"></div>
            </div>

            <div class="mt-8 text-center">
                <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.2em] text-pink-400">Dados de Recebimento</p>
                <h2 class="text-xl font-black text-slate-800">Configure seu PIX</h2>
            </div>
        </div>

        <div class="-mt-6 px-5" x-data="{ pixType: '{{ auth()->user()->pix_type ?: 'CPF' }}' }">
            <div class="rounded-[40px] border border-pink-50 bg-white p-7 shadow-xl shadow-black/5">
                <form action="{{ route('setup.gateway.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Nome do Titular</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">person</span>
                            <input type="text" name="pix_name" value="{{ auth()->user()->pix_name }}" required
                                class="w-full rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white"
                                placeholder="Nome completo conforme banco">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Documento (CPF/CNPJ)</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">badge</span>
                            <input type="text" name="pix_document" value="{{ auth()->user()->pix_document }}" required
                                x-data x-mask:dynamic="$input.length > 14 ? '99.999.999/9999-99' : '999.999.999-99'"
                                class="w-full rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white"
                                placeholder="000.000.000-00">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Tipo de Chave</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">key</span>
                            <select name="pix_type" id="pix_type_page" required x-model="pixType"
                                class="w-full appearance-none rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-10 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white">
                                <option value="">Selecione o tipo</option>
                                <option value="CPF">CPF</option>
                                <option value="Email">E-mail</option>
                                <option value="Telefone">Telefone</option>
                                <option value="Chave Aleatória">Chave Aleatória</option>
                            </select>
                            <span class="material-symbols-outlined pointer-events-none absolute right-4 text-slate-400">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-1.5" x-init="$watch('pixType', value => $refs.pixKeyInput.value = '')">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Chave PIX</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">account_balance_wallet</span>
                            <input type="text" name="pix_key" value="{{ auth()->user()->pix_key }}" required
                                x-ref="pixKeyInput"
                                x-data="{
                                    get mask() {
                                        if (pixType === 'CPF') return '999.999.999-99';
                                        if (pixType === 'Telefone') return '(99) 99999-9999';
                                        return '';
                                    }
                                }"
                                :x-mask="mask"
                                class="w-full rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white"
                                placeholder="Insira sua chave">
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-4 flex w-full items-center justify-center gap-3 rounded-[28px] bg-slate-800 px-6 py-5 font-bold text-white shadow-xl transition-all hover:bg-pink-500 active:scale-[0.98]">
                        <span class="material-symbols-outlined">save</span>
                        <span>Salvar Alterações</span>
                    </button>
                </form>
            </div>

            <div class="mt-8 rounded-[35px] border border-amber-100 bg-amber-50/50 p-6">
                <div class="flex gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <span class="material-symbols-outlined">info</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Atenção aos dados</h4>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">Certifique-se de que a chave PIX pertence ao titular da conta para evitar problemas no processamento do seu saque.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
