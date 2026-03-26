@extends('layouts.blueapp')

@section('content')
    <div x-data="dashboardApp()" class="pb-28">
        {{-- Header & Wallet Card --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-8">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-pink-400">OlÃ¡, {{ $user->name }} âœ¨</p>
                    <h1 class="text-[26px] font-bold tracking-tight text-slate-800">
                        Easter<span class="text-pink-500">Eggs</span><span class="text-blue-400">.</span>
                    </h1>
                </div>

                <div class="flex gap-2">
                    {{-- Check-in --}}
                    @if($userCheckin)
                    <button @click="doCheckin()" class="grid h-12 w-12 place-items-center rounded-[20px] bg-emerald-400 text-white shadow-lg shadow-emerald-100 active:scale-90 transition-all">
                        <span class="material-symbols-outlined text-xl">calendar_today</span>
                    </button>
                    @endif

                    {{-- NotificaÃ§Ã£o --}}
                    <button class="relative grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-[0.98]">
                        <span class="text-xl">ðŸ””</span>
                        <span class="absolute right-2.5 top-2.5 h-3 w-3 rounded-full bg-pink-500 ring-4 ring-white"></span>
                    </button>
                </div>
            </div>

            {{-- Card Pastel Vibrante --}}
            <div class="mt-6 rounded-[32px] bg-gradient-to-br from-[#FFADCC] to-[#FF80A6] px-5 pt-5 pb-5 shadow-xl shadow-pink-200 ring-1 ring-white/30 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/20 blur-2xl"></div>
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-white/90">Meus atalhos</p>
                    <button class="grid h-8 w-8 place-items-center rounded-xl bg-white/20 text-white ring-1 ring-white/20">
                        <span class="text-xl leading-none">â€¦</span>
                    </button>
                </div>

                {{-- Categories / Actions --}}
                <div class="mt-4 flex items-center gap-3 overflow-x-auto no-scrollbar pb-1 pt-1">
                    {{-- Rede --}}
                    <a href="{{ route('user.team') }}" class="flex shrink-0 flex-col items-center gap-2">
                        <span class="grid h-[58px] w-[58px] place-items-center rounded-full border-2 border-dashed border-white/60 bg-white/10 text-white">
                            <span class="text-xl">ðŸ“Š</span>
                        </span>
                        <span class="text-[11px] text-white/90">Rede</span>
                    </a>

                    @php
                        $categories = [
                            ['label' => 'Tarefas', 'icon' => 'ðŸŽ¯', 'link' => route('user.tasks.index')],
                            ['label' => 'DepÃ³sito', 'icon' => 'ðŸ“ˆ', 'link' => route('user.deposit')],
                            ['label' => 'Saque', 'icon' => 'ðŸ¦', 'link' => route('user.withdraw')],
                            ['label' => 'HistÃ³rico', 'icon' => 'ðŸ—“ï¸', 'link' => route('history')],
                            ['label' => 'Perfil', 'icon' => 'ðŸ‘¤', 'link' => route('profile')],
                        ];
                    @endphp

                    @foreach ($categories as $cat)
                        <a href="{{ $cat['link'] }}" class="flex shrink-0 flex-col items-center gap-2 group">
                            <span class="grid h-[62px] w-[62px] place-items-center rounded-[22px] bg-white/20 ring-2 ring-white/40 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-[26px]">{{ $cat['icon'] }}</span>
                            </span>
                            <span class="max-w-[76px] truncate text-[11px] font-semibold text-white">{{ $cat['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Carteira rÃ¡pida --}}
            <div class="mt-5 grid grid-cols-2 gap-4">
                <div class="rounded-[28px] bg-white p-5 shadow-sm border border-pink-50/50">
                    <p class="text-[11px] font-bold text-pink-300 uppercase tracking-wider">Saldo</p>
                    <p class="mt-1 text-xl font-bold text-slate-800">
                        R$ <span x-text="formatMoney(balance)">{{ number_format($walletBalance, 2, ',', '.') }}</span>
                    </p>
                    <p class="mt-2 text-[10px] font-medium text-slate-400">
                        Lucro hoje: <span class="text-emerald-400">+R$ {{ number_format($dailyEarningsToday, 2, ',', '.') }}</span>
                    </p>
                </div>

                <div class="rounded-[28px] bg-white p-5 shadow-sm border border-blue-50/50">
                    <p class="text-[11px] font-bold text-blue-300 uppercase tracking-wider">Rendimento</p>
                    <p class="mt-1 text-xl font-bold text-slate-800">
                        {{ number_format($dailyRate, 1, ',', '.') }}% <span class="text-xs font-medium text-slate-400">/dia</span>
                    </p>
                    <div class="mt-3 flex gap-2">
                        <a href="#investimentos" class="flex-1 inline-flex items-center justify-center rounded-xl bg-pink-500 py-2 text-[10px] font-bold text-white shadow-md shadow-pink-100 active:scale-[0.95] transition-all">
                            Investir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ConteÃºdo principal --}}
        <div class="px-5 pt-4">
            {{-- Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                <button @click="tab = 'todos'" :class="tab === 'todos' ? 'bg-white shadow-sm ring-1 ring-black/5 text-slate-900' : 'bg-white/40 text-slate-500'" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-medium smooth-transition">
                    Todos
                    <span x-show="tab === 'todos'" class="grid h-5 min-w-5 place-items-center rounded-full bg-[#EAF4FF] px-1 text-xs text-[#2C95EF]">âœ“</span>
                </button>

                <button @click="tab = 'ativos'" :class="tab === 'ativos' ? 'bg-white shadow-sm ring-1 ring-black/5 text-slate-900' : 'bg-white/40 text-slate-500'" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-medium smooth-transition">
                    Meus Investimentos
                    <span class="grid h-5 min-w-5 place-items-center rounded-full bg-[#2C95EF] px-1 text-xs text-white">{{ $investments->count() }}</span>
                </button>
            </div>

            {{-- SeÃ§Ã£o: Planos para investir --}}
            <div id="investimentos" class="mt-6" x-show="tab === 'todos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">SeleÃ§Ã£o de Ovos</h2>
                    <a href="#" class="text-xs font-bold text-pink-500">Ver CatÃ¡logo</a>
                </div>

                <div class="mt-4 flex gap-5 overflow-x-auto no-scrollbar pb-6">
                    @php
                        $pastelColors = [
                            ['bg' => 'bg-[#E5D9FF]', 'text' => 'text-[#7C5CC4]', 'btn' => 'bg-[#7C5CC4]'],
                            ['bg' => 'bg-[#FFD9E5]', 'text' => 'text-[#C45C7C]', 'btn' => 'bg-[#C45C7C]'],
                            ['bg' => 'bg-[#D9F2FF]', 'text' => 'text-[#5C94C4]', 'btn' => 'bg-[#5C94C4]'],
                            ['bg' => 'bg-[#F9FFD9]', 'text' => 'text-[#94C45C]', 'btn' => 'bg-[#94C45C]'],
                        ];
                    @endphp
                    @foreach ($packages as $index => $package)
                        @php $color = $pastelColors[$index % count($pastelColors)]; @endphp
                        <div class="w-[200px] shrink-0 rounded-[40px] {{ $color['bg'] }} p-6 shadow-xl shadow-black/5 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/20 group-hover:scale-125 transition-transform duration-500"></div>

                            <div class="relative flex flex-col items-center text-center">
                                <div class="mb-4 h-28 w-20 flex items-center justify-center">
                                    {{-- Placeholder para o Ovo --}}
                                    <svg class="h-full w-full drop-shadow-lg" viewBox="0 0 100 120">
                                        <path d="M50 0C22.3858 0 0 44.7715 0 100C0 111.046 22.3858 120 50 120C77.6142 120 100 111.046 100 100C100 44.7715 77.6142 0 50 0Z" fill="white" fill-opacity="0.6" />
                                        <path d="M50 10C30 10 15 45 15 90C15 105 30 110 50 110C70 110 85 105 85 90C85 45 70 10 50 10Z" fill="white" fill-opacity="0.3" />
                                    </svg>
                                </div>

                                <h3 class="text-lg font-bold {{ $color['text'] }}">{{ $package->name }}</h3>
                                <p class="mt-1 text-[11px] font-bold {{ $color['text'] }} opacity-60 uppercase">{{ $package->validity }} dias</p>

                                <div class="mt-4 mb-2">
                                    <p class="text-2xl font-black {{ $color['text'] }}">R$ {{ number_format($package->price, 0, ',', '.') }}</p>
                                </div>

                                <button @click="openConfirmModal({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})"
                                    class="mt-2 h-10 w-10 rounded-full bg-white flex items-center justify-center shadow-lg active:scale-90 transition-all">
                                    <span class="{{ $color['text'] }} text-xl">ï¼‹</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SeÃ§Ã£o: Meus Investimentos --}}
            <div class="mt-6" x-show="tab === 'ativos' || tab === 'todos'" x-transition>
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Meus investimentos</h2>
                    <button class="grid h-9 w-9 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                        <span class="text-xl leading-none">â€¦</span>
                    </button>
                </div>

                <div class="mt-3 space-y-4">
                    @forelse ($investments as $inv)
                        <div class="flex items-center gap-4 rounded-[28px] bg-white p-5 shadow-sm border border-slate-100">
                            <div class="relative">
                                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-blue-50 text-[#2C95EF]">
                                    <span class="text-2xl">ðŸ’¼</span>
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
                            <span class="text-4xl block mb-2">ðŸ“¥</span>
                            <p class="text-sm font-medium text-slate-500">VocÃª ainda nÃ£o tem investimentos ativos.</p>
                            <a href="#investimentos" @click="tab = 'todos'" class="mt-4 inline-block text-sm font-bold text-[#2C95EF]">ComeÃ§ar agora â†’</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Purchase Modal --}}
        <div x-show="confirmModal" x-cloak class="fixed inset-0 z-50 flex items-end justify-center sm:items-center p-4">
            <div x-show="confirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="confirmModal = false"></div>

            <div x-show="confirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative w-full max-w-sm bg-white rounded-t-[40px] sm:rounded-[40px] overflow-hidden shadow-2xl">
                <div class="p-8 text-center">
                    <div class="mx-auto w-24 h-24 bg-pink-50 rounded-[30px] flex items-center justify-center mb-6">
                        <span class="text-5xl">ðŸ¥š</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Confirmar investimento?</h3>
                    <p class="text-sm font-medium text-slate-400 mb-6">Deseja adquirir o ovo <span class="font-bold text-pink-500" x-text="selectedPackage.name"></span> por <strong class="text-slate-800">R$ <span x-text="formatMoney(selectedPackage.price)"></span></strong>?</p>

                    <div class="bg-[#F0F7FF] rounded-[30px] p-5 mb-8">
                        <div class="flex justify-between text-xs mb-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider">Saldo atual</span>
                            <span class="text-slate-800 font-black">R$ <span x-text="formatMoney(balance)"></span></span>
                        </div>
                        <div class="flex justify-between text-xs pt-3 border-t border-blue-100">
                            <span class="text-slate-400 font-bold uppercase tracking-wider">Saldo final</span>
                            <span :class="balance < selectedPackage.price ? 'text-red-400' : 'text-emerald-400'" class="font-black">
                                R$ <span x-text="formatMoney(balance - selectedPackage.price)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <button @click="confirmPurchase()" :disabled="loading || balance < selectedPackage.price"
                            class="w-full rounded-[24px] bg-pink-500 py-4 text-sm font-bold text-white shadow-xl shadow-pink-100 active:scale-[0.98] disabled:opacity-50 transition-all flex items-center justify-center gap-2">
                            <template x-if="loading">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="loading ? 'Gerando Ovo...' : 'Confirmar Coleta'"></span>
                        </button>

                        <button @click="confirmModal = false" class="w-full py-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Navigation --}}
        <div class="fixed bottom-0 left-0 right-0 mx-auto max-w-[420px] bg-white/90 backdrop-blur-xl px-8 pb-10 pt-4 border-t border-pink-50 z-40 rounded-t-[40px] shadow-[0_-10px_40px_rgba(255,128,166,0.1)]">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 group">
                    <div class="h-12 w-12 rounded-2xl bg-pink-500 flex items-center justify-center text-white shadow-lg shadow-pink-200">
                        <span class="material-symbols-outlined">home</span>
                    </div>
                </a>

                <a href="{{ route('user.team') }}" class="flex flex-col items-center gap-1 group">
                    <div class="h-12 w-12 rounded-2xl bg-white flex items-center justify-center text-slate-400 group-hover:text-pink-400 transition-colors">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                </a>

                <a href="{{ route('user.deposit') }}" class="flex flex-col items-center gap-1 group">
                    <div class="h-12 w-12 rounded-2xl bg-white flex items-center justify-center text-slate-400 group-hover:text-pink-400 transition-colors">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                    </div>
                </a>

                <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 group">
                    <div class="h-12 w-12 rounded-2xl bg-white flex items-center justify-center text-slate-400 group-hover:text-pink-400 transition-colors">
                        <span class="material-symbols-outlined">person</span>
                    </div>
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
                        alert('Erro de conexÃ£o com o servidor.');
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
                if (window.showToast) {
                    window.showToast(result.message, 'success');
                } else {
                    alert(result.message);
                }
                setTimeout(() => window.location.reload(), 2000);
                    } catch (error) {
                if (window.showToast) {
                    window.showToast('Erro ao realizar check-in', 'error');
                } else {
                    alert('Erro ao realizar check-in');
                }
                    }
                }
            }
        }
    </script>
    @endpush
@endsection

