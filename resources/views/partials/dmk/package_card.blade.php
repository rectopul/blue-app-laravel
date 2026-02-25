<div
    class="w-full bg-white border border-slate-100 rounded-[28px] p-5 shadow-sm hover:shadow-md transition-shadow duration-300">
    <div class="flex items-center gap-4 mb-5">
        <div class="relative w-20 h-20 shrink-0">
            <img src="{{ asset(main_root() . '/' . $package->photo) }}" alt="{{ $package->name }}"
                class="w-full h-full object-cover rounded-2xl shadow-inner border border-slate-50">
            <div
                class="absolute -top-2 -right-2 bg-elm-500 text-white text-[9px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">
                Ativo
            </div>
        </div>

        <div class="flex flex-col">
            <h2 class="text-lg font-black text-slate-800 leading-tight mb-1">{{ $package->name }}</h2>
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-slate-400 !text-sm">schedule</span>
                <span class="text-xs text-slate-500 font-medium">Ciclo: <b
                        class="text-slate-800">{{ $package->validity }} dias</b></span>
            </div>
        </div>
    </div>

    <div class="bg-slate-50 rounded-2xl p-4 grid grid-cols-2 gap-4 relative mb-5 border border-slate-100/50">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-px h-8 bg-slate-200"></div>

        <div class="flex flex-col items-center text-center">
            @php
                $valueProfit = $package->price * ($package->commission_with_avg_amount / 100);
            @endphp
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter mb-1">Renda Diária</span>
            <span class="text-base font-black text-elm-600 tracking-tight">{{ price($valueProfit) }}</span>
        </div>

        <div class="flex flex-col items-center text-center">
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter mb-1">Retorno Total</span>
            <span
                class="text-base font-black text-slate-800 tracking-tight">{{ price($package->total_return_amount) }}</span>
        </div>
    </div>

    <div class="flex justify-between items-center gap-4">
        <div class="flex flex-col">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Custo</span>
            <span class="text-xl font-black text-slate-900 tracking-tighter">{{ price($package->price) }}</span>
        </div>

        <button data-id="{{ $package->id }}" data-name="{{ $package->name }}" data-price="{{ $package->price }}"
            data-validity="{{ $package->validity }}" data-photo="{{ $package->photo }}"
            data-daily-return="{{ price($valueProfit) }}"
            data-total-return="{{ price($package->total_return_amount) }}"
            class="btn-investir h-12 px-8 bg-slate-900 hover:bg-elm-600 active:scale-95 text-white font-bold text-sm rounded-xl transition-all duration-200 shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
            <span>Investir</span>
            <span class="material-symbols-outlined !text-sm">arrow_forward</span>
        </button>
    </div>
</div>
