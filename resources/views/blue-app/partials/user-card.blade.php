<div class="bg-white rounded-[24px] p-4 flex items-center justify-between shadow-sm border border-slate-100">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
            <span class="material-symbols-outlined">person</span>
        </div>
        <div>
            <p class="text-slate-900 font-bold text-sm">{{ substr($u->phone, 0, 4) . '****' . substr($u->phone, -4) }}</p>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-900 text-white uppercase tracking-tighter">Nível {{ $lv }}</span>
                <span class="text-[10px] font-medium text-slate-400">Dep: R$ {{ number_format($u->deposits_sum_amount ?? 0, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-end">
        @if(($u->deposits_sum_amount ?? 0) > 0)
            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 !text-sm">verified</span>
            </div>
        @else
            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-300 !text-sm">pending</span>
            </div>
        @endif
        <p class="text-[9px] font-bold text-slate-300 uppercase mt-1">{{ \Carbon\Carbon::parse($u->created_at)->format('d/m/y') }}</p>
    </div>
</div>
