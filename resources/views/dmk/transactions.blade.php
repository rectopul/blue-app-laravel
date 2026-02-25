@extends('layouts.dmk')

@section('content')
    <div class="min-h-screen bg-mirage-950 pb-24">
        <div class="bg-mirage-900 pt-10 pb-12 px-6 rounded-b-[40px] shadow-lg">
            <div class="flex flex-col items-center text-center">
                <span class="text-slate-400 text-[10px] font-black uppercase tracking-[3px] mb-2">Saldo em Conta</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-emerald-500 text-lg font-bold">R$</span>
                    <span
                        class="text-elm-800 text-4xl font-black tracking-tighter">{{ number_format(user()->balance, 2, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex gap-1 mt-8 overflow-x-auto pb-2 no-scrollbar">
                <button id="show-all-btn"
                    class="filter-btn-new active px-6 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all uppercase tracking-wider"
                    data-filter="all">
                    Todos
                </button>
                <button
                    class="filter-btn-new px-6 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all uppercase tracking-wider flex items-center gap-2"
                    data-filter="deposit">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Depósitos
                </button>
                <button
                    class="filter-btn-new px-6 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all uppercase tracking-wider flex items-center gap-2"
                    data-filter="withdraw">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Saques
                </button>
                <button
                    class="filter-btn-new px-6 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all uppercase tracking-wider flex items-center gap-2"
                    data-filter="purchase">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span> Compras
                </button>
                <button
                    class="filter-btn-new px-6 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all uppercase tracking-wider flex items-center gap-2"
                    data-filter="comission">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Comissões
                </button>
            </div>
        </div>

        <div class="px-6 mt-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-white font-black text-sm uppercase tracking-widest">Atividade Recente</h3>
                <span class="text-[10px] text-slate-500 font-bold uppercase" id="item-count">Carregando...</span>
            </div>

            <div class="space-y-3" id="transactions-container">
                @if ($deposits->isEmpty() && $withdrawals->isEmpty() && $ledgers->isEmpty() && $commissions->isEmpty())
                    <div class="text-center py-20" id="no-records">
                        <span class="material-symbols-outlined text-slate-700 !text-6xl mb-4">history_toggle_off</span>
                        <p class="text-slate-500 text-sm font-bold uppercase">Nenhum registro encontrado</p>
                    </div>
                @else
                    {{-- O Container de ITENS permanece com a lógica de inclusão dos seus partials --}}
                    @foreach ($deposits as $deposit)
                        <div class="transaction-item transition-all duration-300" data-type="deposit">
                            @include('partials.dmk.transaction_card', [
                                'type' => 'deposit',
                                'data' => $deposit,
                            ])
                        </div>
                    @endforeach

                    @foreach ($withdrawals as $withdrawal)
                        <div class="transaction-item transition-all duration-300" data-type="withdraw">
                            @include('partials.dmk.transaction_card', [
                                'type' => 'withdraw',
                                'data' => $withdrawal,
                            ])
                        </div>
                    @endforeach

                    @foreach ($ledgers as $ledger)
                        <div class="transaction-item transition-all duration-300" data-type="purchase">
                            @include('partials.dmk.transaction_card', [
                                'type' => 'purchase',
                                'data' => $ledger,
                            ])
                        </div>
                    @endforeach

                    @foreach ($commissions as $commission)
                        <div class="transaction-item transition-all duration-300" data-type="comission">
                            @include('partials.dmk.transaction_card', [
                                'type' => 'comission',
                                'data' => $commission,
                            ])
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Estilização Customizada para os Filtros */
        .filter-btn-new {
            background: rgba(255, 255, 255, 0.03);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .filter-btn-new.active {
            background: #fff;
            color: #0f172a;
            box-shadow: 0 2px 10px -5px rgba(0, 0, 0, 0.3);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn-new');
            const transactionItems = document.querySelectorAll('.transaction-item');
            const noRecordsDiv = document.getElementById('no-records');
            const itemCountSpan = document.getElementById('item-count');

            function applyFilter(filterType) {
                let visibleCount = 0;

                transactionItems.forEach(item => {
                    if (filterType === 'all' || item.dataset.type === filterType) {
                        item.style.display = 'block';
                        // Pequeno delay para efeito de fade-in
                        setTimeout(() => item.style.opacity = '1', 10);
                        visibleCount++;
                    } else {
                        item.style.opacity = '0';
                        item.style.display = 'none';
                    }
                });

                // Atualiza contador
                itemCountSpan.innerText = `${visibleCount} Operações`;

                // Controle de estado vazio
                if (noRecordsDiv) {
                    noRecordsDiv.style.display = visibleCount === 0 ? 'block' : 'none';
                }

                // Atualiza botões
                filterButtons.forEach(btn => {
                    if (btn.dataset.filter === filterType) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', () => applyFilter(button.dataset.filter));
            });

            // Inicializa
            applyFilter('all');
        });
    </script>
@endsection
