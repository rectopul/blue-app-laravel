@php
    // Definições refinadas para a nova UX
    $transactionConfig = [
        'deposit' => [
            'icon' => 'payments', // Material Symbol
            'label' => 'Depósito Recebido',
            'bg' => 'bg-emerald-500/10',
            'text' => 'text-emerald-500',
            'symbol' => '+',
        ],
        'withdraw' => [
            'icon' => 'account_balance_wallet',
            'label' => 'Retirada de Saldo',
            'bg' => 'bg-amber-500/10',
            'text' => 'text-amber-500',
            'symbol' => '-',
        ],
        'purchase' => [
            'icon' => 'shopping_cart',
            'label' => 'Compra Realizada',
            'bg' => 'bg-blue-500/10',
            'text' => 'text-blue-500',
            'symbol' => '-',
        ],
        'comission' => [
            'icon' => 'group_add',
            'label' => 'Bônus de Comissão',
            'bg' => 'bg-purple-500/10',
            'text' => 'text-purple-500',
            'symbol' => '+',
        ],
    ];

    $config = $transactionConfig[$type] ?? $transactionConfig['deposit'];

    // Lógica de cores para status
    $statusColor = 'text-slate-400';
    if (isset($data->status)) {
        if ($data->status == 'approved' || $data->status == 'concluído') {
            $statusColor = 'text-emerald-500';
        }
        if ($data->status == 'pending' || $data->status == 'pendente') {
            $statusColor = 'text-amber-500';
        }
        if ($data->status == 'rejected' || $data->status == 'recusado') {
            $statusColor = 'text-red-500';
        }
    }
@endphp

<div
    class="w-full p-4 flex items-center gap-4 bg-white rounded-[24px] shadow-sm border border-slate-50 mb-1 active:scale-[0.98] transition-transform">
    <div
        class="w-12 h-12 rounded-2xl {{ $config['bg'] }} {{ $config['text'] }} flex justify-center items-center shrink-0">
        <span class="material-symbols-outlined !text-2xl">
            {{ $config['icon'] }}
        </span>
    </div>

    <div class="flex flex-col min-w-0">
        <h4 class="text-slate-900 text-sm font-black leading-tight truncate">
            {{ $config['label'] }}
        </h4>
        <div class="flex items-center gap-2 mt-0.5">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-tight">
                {{ date('d/m/Y H:i', strtotime($data->created_at)) }}
            </span>
            @if (isset($data->status))
                <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                <span class="text-[9px] font-black uppercase {{ $statusColor }}">
                    {{ $data->status }}
                </span>
            @endif
        </div>
    </div>

    <div class="ml-auto text-right shrink-0">
        <p
            class="text-sm font-black tracking-tighter {{ $config['symbol'] == '+' ? 'text-emerald-600' : 'text-slate-900' }}">
            {{ $config['symbol'] }} {{ price($data->amount) }}
        </p>
        <p class="text-[9px] font-bold text-slate-300 uppercase tracking-tighter">
            Tax: R$ 0,00
        </p>
    </div>
</div>
