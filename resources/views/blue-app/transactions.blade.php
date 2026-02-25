@extends('layouts.blueapp')

@section('content')
    <div class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[34px] bg-gradient-to-b from-[#CFE7FF] to-[#EEF4F9] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('profile') }}"
                    class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-slate-600">arrow_back</span>
                </a>
                <h1 class="text-lg font-bold text-slate-900 uppercase tracking-widest">Minhas Transações</h1>
                <div class="w-11"></div>
            </div>
        </div>

        {{-- Transactions List --}}
        <div class="px-5 mt-4">
            <div class="space-y-3">
                @forelse($ledgers as $ledger)
                    <div
                        class="bg-white rounded-[24px] p-5 shadow-sm border border-slate-100 flex items-center justify-between group active:scale-[0.98] transition-all">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center
                                {{ $ledger->credit > 0 ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500' }}">
                                <span class="material-symbols-outlined text-2xl">
                                    {{ $ledger->credit > 0 ? 'arrow_downward' : 'arrow_upward' }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate uppercase tracking-tight">
                                    {{ str_replace('_', ' ', $ledger->reason) }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5">
                                    {{ $ledger->created_at->format('d/m/Y • H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p
                                class="text-base font-black tracking-tight {{ $ledger->credit > 0 ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ $ledger->credit > 0 ? '+' : '-' }} R$
                                {{ number_format($ledger->credit > 0 ? $ledger->credit : $ledger->debit, 2, ',', '.') }}
                            </p>
                            <span
                                class="text-[8px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider
                                {{ $ledger->status === 'approved' || $ledger->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $ledger->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center bg-white rounded-[40px] border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-slate-300 text-4xl">history</span>
                        </div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Nenhuma transação encontrada
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $ledgers->links() }}
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <style>
            .pagination {
                display: flex;
                justify-content: center;
                gap: 0.5rem;
            }

            .page-item .page-link {
                padding: 0.5rem 1rem;
                border-radius: 0.75rem;
                background: white;
                font-size: 0.875rem;
                font-weight: 700;
                color: #64748b;
                border: 1px solid #f1f5f9;
            }

            .page-item.active .page-link {
                background: #2C95EF;
                color: white;
                border-color: #2C95EF;
            }
        </style>
    @endpush
@endsection
