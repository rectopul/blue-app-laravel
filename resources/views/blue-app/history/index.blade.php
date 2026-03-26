@extends('layouts.blueapp')

@section('content')
    <div x-data="{ filter: 'all' }" class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Histórico <span class="text-pink-500">Geral</span></h1>
                <div class="w-12"></div>
            </div>

            {{-- Filter Tabs --}}
            <div class="mt-8 flex gap-2 overflow-x-auto no-scrollbar pb-2">
                <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-pink-500 text-white shadow-lg shadow-pink-200' : 'bg-white text-slate-400 border border-pink-50'"
                    class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                    Todos
                </button>
                <button @click="filter = 'deposit'" :class="filter === 'deposit' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white text-slate-400 border border-blue-50'"
                    class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                    Depósitos
                </button>
                <button @click="filter = 'withdraw'" :class="filter === 'withdraw' ? 'bg-amber-500 text-white shadow-lg' : 'bg-white text-slate-400 border border-amber-50'"
                    class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                    Saques
                </button>
                <button @click="filter = 'comission'" :class="filter === 'comission' ? 'bg-emerald-500 text-white shadow-lg' : 'bg-white text-slate-400 border border-emerald-50'"
                    class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                    Comissões
                </button>
            </div>
        </div>

        {{-- Transaction List --}}
        <div class="px-5 mt-8 space-y-4">
            {{-- Loop through data and show based on filter --}}
            @php
                $allTransactions = collect();
                foreach($deposits as $d) { $d->type = 'deposit'; $d->date_val = $d->created_at; $allTransactions->push($d); }
                foreach($withdrawals as $w) { $w->type = 'withdraw'; $w->date_val = $w->created_at; $allTransactions->push($w); }
                foreach($commissions as $c) { $c->type = 'comission'; $c->date_val = $c->created_at; $allTransactions->push($c); }
                foreach($ledgers as $l) {
                    if(!in_array($l->reason, ['deposit', 'withdraw', 'commission'])) {
                        $l->type = 'other'; $l->date_val = $l->created_at; $allTransactions->push($l);
                    }
                }
                $allTransactions = $allTransactions->sortByDesc('date_val');
            @endphp

            @forelse($allTransactions as $t)
                <div x-show="filter === 'all' || filter === '{{ $t->type }}'"
                    class="bg-white p-5 rounded-[30px] shadow-xl shadow-black/5 border border-slate-50 flex items-center gap-4 transition-all">

                    @php
                        $icon = match($t->type) {
                            'deposit' => '📈',
                            'withdraw' => '🏦',
                            'comission' => '🎁',
                            default => '📝'
                        };
                        $bgColor = match($t->type) {
                            'deposit' => 'bg-blue-50 text-blue-500',
                            'withdraw' => 'bg-amber-50 text-amber-500',
                            'comission' => 'bg-emerald-50 text-emerald-500',
                            default => 'bg-slate-50 text-slate-500'
                        };
                    @endphp

                    <div class="w-14 h-14 rounded-2xl {{ $bgColor }} flex items-center justify-center text-2xl shrink-0">
                        {{ $icon }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between items-start gap-2">
                            <h3 class="text-xs font-black text-slate-800 uppercase truncate">
                                {{ $t->method_name ?? $t->reason ?? $t->perticulation ?? 'Transação' }}
                            </h3>
                            <span class="text-[14px] font-black {{ $t->type == 'withdraw' ? 'text-pink-500' : 'text-emerald-500' }}">
                                {{ $t->type == 'withdraw' ? '-' : '+' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($t->date_val)->format('d/m/Y H:i') }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest
                                {{ $t->status == 'approved' || $t->status == 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $t->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white rounded-[40px] border border-dashed border-slate-200">
                    <span class="text-5xl block mb-4">📭</span>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Nenhum registro encontrado</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
