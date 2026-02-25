<div class="w-full grid grid-cols-12 gap-3">
    <div class="col-span-7 relative overflow-hidden bg-slate-900 rounded-[24px] p-5 shadow-lg shadow-slate-200">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-elm-500/10 rounded-full blur-2xl"></div>

        <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
                <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Saldo Disponível</span>
                <h2 class="text-white text-2xl font-black mt-1 tracking-tight">
                    {{ price($user->balance) }}
                </h2>
            </div>

            <div class="mt-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-elm-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-elm-400 !text-[18px]">
                        account_balance_wallet
                    </span>
                </div>
                <span class="text-elm-400 text-[10px] font-bold uppercase tracking-tighter">Conta Protegida</span>
            </div>
        </div>
    </div>

    <div class="col-span-5 flex flex-col gap-2">
        <a href="{{ route('user.deposit') }}"
            class="flex-1 bg-elm-500 hover:bg-elm-600 active:scale-95 transition-all text-white flex flex-col justify-center items-start px-4 rounded-[20px] shadow-sm relative overflow-hidden group">
            <span class="text-[10px] font-bold uppercase opacity-80 mb-1">Entrada</span>
            <div class="flex items-center justify-between w-full">
                <span class="font-bold text-sm">Depósito</span>
                <span class="material-symbols-outlined !text-[18px] group-hover:translate-x-1 transition-transform">
                    north_east
                </span>
            </div>
        </a>

        <a href="{{ route('user.withdraw') }}"
            class="flex-1 bg-white border border-slate-200 hover:border-elm-500 active:scale-95 transition-all text-slate-800 flex flex-col justify-center items-start px-4 rounded-[20px] shadow-sm group">
            <span class="text-[10px] text-slate-400 font-bold uppercase mb-1">Saída</span>
            <div class="flex items-center justify-between w-full">
                <span class="font-bold text-sm">Saque</span>
                <span
                    class="material-symbols-outlined !text-[18px] text-slate-400 group-hover:translate-x-1 transition-transform">
                    south_east
                </span>
            </div>
        </a>
    </div>
</div>
