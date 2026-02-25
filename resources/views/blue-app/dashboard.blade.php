@extends('layouts.blueapp')

@section('content')
    <div x-data="dashboardApp()" class="pb-28">
        {{-- Header & Wallet Card --}}
        <div class="relative overflow-hidden rounded-b-[34px] bg-gradient-to-b from-[#CFE7FF] via-[#B9DDFF] to-[#EEF4F9] px-5 pt-6 pb-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-600/80">Bem-vindo, {{ $user->name }}</p>
                    <h1 class="text-[22px] font-semibold tracking-tight">
                        Invest<span class="text-slate-900">Loop</span><span class="text-[#2C95EF]">.</span>
                    </h1>
                </div>

                <div class="flex gap-2">
                    {{-- Check-in --}}
                    @if($userCheckin)
                    <button @click="doCheckin()" class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-500 text-white shadow-sm active:scale-90 transition-all">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                    </button>
                    @endif

                    {{-- Notificação --}}
                    <button class="relative grid h-11 w-11 place-items-center rounded-2xl bg-white/90 shadow-sm ring-1 ring-black/5 active:scale-[0.98]">
                        <span class="text-lg">🔔</span>
                        <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-[#2C95EF] ring-2 ring-white"></span>
                    </button>
                </div>
            </div>

            {{-- Card Azul grande --}}
            <div class="mt-5 rounded-[22px] bg-gradient-to-b from-[#57B0FF] to-[#2C95EF] px-4 pt-4 pb-4 shadow-lg shadow-blue-500/20 ring-1 ring-white/20">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-white/90">Meus atalhos</p>
                    <button class="grid h-8 w-8 place-items-center rounded-xl bg-white/20 text-white ring-1 ring-white/20">
                        <span class="text-xl leading-none">…</span>
                    </button>
                </div>

                {{-- Categories / Actions --}}
                <div class="mt-4 flex items-center gap-3 overflow-x-auto no-scrollbar pb-1 pt-1">
                    {{-- Rede --}}
                    <a href="{{ route('user.team') }}" class="flex shrink-0 flex-col items-center gap-2">
                        <span class="grid h-[58px] w-[58px] place-items-center rounded-full border-2 border-dashed border-white/60 bg-white/10 text-white">
                            <span class="text-xl">📊</span>
                        </span>
                        <span class="text-[11px] text-white/90">Rede</span>
                    </a>

                    @php
                        $categories = [
                            ['label' => 'Depósito', 'icon' => '📈', 'link' => route('user.deposit')],
                            ['label' => 'Saque', 'icon' => '🏦', 'link' => route('user.withdraw')],
                            ['label' => 'Histórico', 'icon' => '🗓️', 'link' => route('history')],
                            ['label' => 'Perfil', 'icon' => '👤', 'link' => route('profile')],
                        ];
                    @endphp

                    @foreach ($categories as $cat)
                        <a href="{{ $cat['link'] }}" class="flex shrink-0 flex-col items-center gap-2">
                            <span class="grid h-[58px] w-[58px] place-items-center rounded-full bg-white/15 ring-2 ring-white/60">
                                <span class="text-xl">{{ $cat['icon'] }}</span>
                            </span>
                            <span class="max-w-[76px] truncate text-[11px] text-white/90">{{ $cat['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Carteira rápida --}}
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-white/80 p-4 shadow-sm ring-1 ring-black/5">
                    <p class="text-xs text-slate-500">Saldo disponível</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">
                        R$ <span x-text="formatMoney(balance)">{{ number_format($walletBalance, 2, ',', '.') }}</span>
                    </p>
                    <p class="mt-2 text-xs text-slate-500">
                        Hoje: <span class="font-medium text-[#0E7FE7]">+R$ {{ number_format($dailyEarningsToday, 2, ',', '.') }}</span>
                    </p>
                </div>

                <div class="rounded-2xl bg-white/80 p-4 shadow-sm ring-1 ring-black/5">
                    <p class="text-xs text-slate-500">Taxa diária média</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">
                        {{ number_format($dailyRate, 1, ',', '.') }}% <span class="text-sm font-medium text-slate-500">/dia</span>
                    </p>
                    <div class="mt-2 flex gap-2">
                        <a href="#investimentos" class="inline-flex items-center justify-center rounded-xl bg-[#2C95EF] px-3 py-2 text-xs font-medium text-white shadow-sm active:scale-[0.98] transition-all">
                            Investir
                        </a>
                        <a href="{{ route('user.withdraw') }}" class="inline-flex items-center justify-center rounded-xl bg-[#EAF4FF] px-3 py-2 text-xs font-medium text-[#2C95EF] ring-1 ring-black/5 active:scale-[0.98] transition-all">
                            Retirar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Conteúdo principal --}}
        <div class="px-5 pt-4">
            {{-- Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                <button @click="tab = 'todos'" :class="tab === 'todos' ? 'bg-white shadow-sm ring-1 ring-black/5 text-slate-900' : 'bg-white/40 text-slate-500'" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-medium smooth-transition">
                    Todos
                    <span x-show="tab === 'todos'" class="grid h-5 min-w-5 place-items-center rounded-full bg-[#EAF4FF] px-1 text-xs text-[#2C95EF]">✓</span>
                </button>

                <button @click="tab = 'ativos'" :class="tab === 'ativos' ? 'bg-white shadow-sm ring-1 ring-black/5 text-slate-900' : 'bg-white/40 text-slate-500'" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-medium smooth-transition">
                    Meus Investimentos
                    <span class="grid h-5 min-w-5 place-items-center rounded-full bg-[#2C95EF] px-1 text-xs text-white">{{ $investments->count() }}</span>
                </button>
            </div>

            {{-- Seção: Planos para investir --}}
            <div id="investimentos" class="mt-5" x-show="tab === 'todos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Investimentos disponíveis</h2>
                    <a href="#" class="text-sm font-medium text-[#2C95EF]">Ver todos</a>
                </div>

                <div class="mt-3 flex gap-4 overflow-x-auto no-scrollbar pb-4">
                    @foreach ($packages as $package)
                        <div class="w-[260px] shrink-0 rounded-[28px] bg-white p-5 shadow-sm border border-slate-100 relative overflow-hidden group">
                            {{-- Decorativo --}}
                            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50 opacity-50 group-hover:scale-110 transition-transform"></div>

                            <div class="relative">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-base font-bold text-slate-900">{{ $package->name }}</p>
                                        <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $package->validity }} dias • {{ number_format($package->commission_with_avg_amount / $package->validity, 2, ',', '.') }}%/dia</p>
                                    </div>
                                    <span class="rounded-xl bg-[#EAF4FF] px-2.5 py-1 text-[10px] font-bold text-[#2C95EF] uppercase">
                                        {{ $package->validity > 15 ? 'Alta Performance' : 'Curto Prazo' }}
                                    </span>
                                </div>

                                <div class="mt-6 flex items-end justify-between">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">A partir de</p>
                                        <p class="text-lg font-black text-slate-900">R$ {{ number_format($package->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Retorno Total</p>
                                        <p class="text-sm font-bold text-emerald-500">+{{ number_format($package->commission_with_avg_amount, 0) }}%</p>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full w-[70%] rounded-full bg-gradient-to-r from-blue-400 to-[#2C95EF]"></div>
                                    </div>
                                </div>

                                <button @click="openConfirmModal({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})"
                                    class="mt-6 w-full rounded-2xl bg-[#2C95EF] py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 active:scale-[0.95] transition-all">
                                    Aplicar agora
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Seção: Meus Investimentos --}}
            <div class="mt-6" x-show="tab === 'ativos' || tab === 'todos'" x-transition>
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Meus investimentos</h2>
                    <button class="grid h-9 w-9 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                        <span class="text-xl leading-none">…</span>
                    </button>
                </div>

                <div class="mt-3 space-y-4">
                    @forelse ($investments as $inv)
                        <div class="flex items-center gap-4 rounded-[28px] bg-white p-5 shadow-sm border border-slate-100">
                            <div class="relative">
                                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-blue-50 text-[#2C95EF]">
                                    <span class="text-2xl">💼</span>
                                </div>
                                <span class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full {{ $inv->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }} border-4 border-white"></span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $inv->package->name }}</p>
                                        <p class="text-[10px] font-medium text-slate-500">Expira em {{ \Carbon\Carbon::parse($inv->expires_at)->format('d/m/Y') }}</p>
                                    </div>
                                    <p class="shrink-0 text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($inv->created_at)->diffForHumans() }}</p>
                                </div>

                                <div class="mt-4">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Progresso do contrato</span>
                                        @php
                                            $start = \Carbon\Carbon::parse($inv->purchased_at);
                                            $end = \Carbon\Carbon::parse($inv->expires_at);
                                            $totalDays = $start->diffInDays($end) ?: 1;
                                            $daysPassed = $start->diffInDays(now());
                                            $progress = min(100, round(($daysPassed / $totalDays) * 100));
                                        @endphp
                                        <span class="text-[10px] font-bold text-[#2C95EF]">{{ $progress }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full bg-[#2C95EF] rounded-full smooth-transition" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between border-t border-slate-50 pt-3">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Investido</p>
                                        <p class="text-sm font-bold text-slate-900">R$ {{ number_format($inv->amount, 2, ',', '.') }}</p>
                                    </div>
                                    <span class="rounded-xl px-3 py-1 text-[10px] font-bold uppercase
                                        {{ $inv->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $inv->status === 'active' ? 'Em rendimento' : $inv->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center bg-white rounded-[28px] border border-dashed border-slate-200">
                            <span class="text-4xl block mb-2">📥</span>
                            <p class="text-sm font-medium text-slate-500">Você ainda não tem investimentos ativos.</p>
                            <a href="#investimentos" @click="tab = 'todos'" class="mt-4 inline-block text-sm font-bold text-[#2C95EF]">Começar agora →</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Purchase Modal --}}
        <div x-show="confirmModal" x-cloak class="fixed inset-0 z-50 flex items-end justify-center sm:items-center p-4">
            <div x-show="confirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="confirmModal = false"></div>

            <div x-show="confirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative w-full max-w-sm bg-white rounded-t-[32px] sm:rounded-[32px] overflow-hidden shadow-2xl">
                <div class="p-8 text-center">
                    <div class="mx-auto w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                        <span class="text-4xl">💎</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Confirmar Investimento</h3>
                    <p class="text-sm text-slate-500 mb-6">Deseja aplicar <strong class="text-slate-900">R$ <span x-text="formatMoney(selectedPackage.price)"></span></strong> no plano <span class="font-bold text-[#2C95EF]" x-text="selectedPackage.name"></span>?</p>

                    <div class="bg-slate-50 rounded-2xl p-4 mb-8">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-slate-500 font-medium">Seu saldo atual:</span>
                            <span class="text-slate-900 font-bold">R$ <span x-text="formatMoney(balance)"></span></span>
                        </div>
                        <div class="flex justify-between text-xs pt-2 border-t border-slate-200">
                            <span class="text-slate-500 font-medium">Saldo após compra:</span>
                            <span :class="balance < selectedPackage.price ? 'text-red-500' : 'text-emerald-500'" class="font-bold">
                                R$ <span x-text="formatMoney(balance - selectedPackage.price)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button @click="confirmPurchase()" :disabled="loading || balance < selectedPackage.price"
                            class="w-full rounded-2xl bg-[#2C95EF] py-4 text-sm font-bold text-white shadow-lg shadow-blue-100 active:scale-[0.98] disabled:opacity-50 transition-all flex items-center justify-center gap-2">
                            <template x-if="loading">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="loading ? 'Processando...' : 'Confirmar e Ativar'"></span>
                        </button>

                        <button @click="confirmModal = false" class="w-full py-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Navigation --}}
        <div class="fixed bottom-0 left-0 right-0 mx-auto max-w-[420px] bg-white/80 backdrop-blur-md px-6 pb-8 pt-4 border-t border-slate-100 z-40">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-2xl bg-[#2C95EF] text-white shadow-lg shadow-blue-200">
                    <span class="material-symbols-outlined">home</span>
                </a>

                <a href="{{ route('user.deposit') }}" class="flex h-14 flex-1 items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 text-sm font-bold text-white shadow-xl active:scale-[0.98] transition-all">
                    <span class="text-xl">＋</span>
                    Novo Depósito
                </a>

                <a href="{{ route('profile') }}" class="grid h-12 w-12 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5 text-slate-400 hover:text-[#2C95EF] transition-colors">
                    <span class="material-symbols-outlined">person</span>
                </a>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush

    @push('scripts')
    <script>
        function dashboardApp() {
            return {
                tab: 'todos',
                balance: {{ (float)$walletBalance }},
                confirmModal: false,
                loading: false,
                selectedPackage: {
                    id: null,
                    name: '',
                    price: 0
                },
                formatMoney(value) {
                    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
                },
                openConfirmModal(id, name, price) {
                    this.selectedPackage = { id, name, price };
                    this.confirmModal = true;
                },
                async confirmPurchase() {
                    if (this.balance < this.selectedPackage.price) {
                        alert('Saldo insuficiente');
                        return;
                    }

                    this.loading = true;
                    try {
                        const response = await fetch("{{ route('api.purchase.confirmation', ['id' => ':id']) }}".replace(':id', this.selectedPackage.id), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer {{ $token }}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                confirmation: true
                            })
                        });

                        const result = await response.json();

                        if (response.ok && result.status === 'success') {
                            alert(result.message);
                            window.location.reload();
                        } else {
                            alert(result.message || 'Erro ao processar compra.');
                            this.loading = false;
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        alert('Erro de conexão com o servidor.');
                        this.loading = false;
                    }
                },
                async doCheckin() {
                    try {
                        const response = await fetch("{{ route('checkins.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const result = await response.json();
                        alert(result.message);
                        window.location.reload();
                    } catch (error) {
                        alert('Erro ao realizar check-in');
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
