@extends('layouts.dmk')

@section('content')
    @php
        $activeCount = $purchases->count();

        $totalInvested = $purchases->sum(function ($p) {
            return $p->package?->price ?? 0;
        });

        $totalIncome = $purchases->sum(function ($p) {
            return $p->total_income ?? 0;
        });

        $totalDailyIncome = $purchases->sum(function ($p) {
            $pkg = $p->package;
            if (!$pkg) {
                return 0;
            }
            return $pkg->price * ($pkg->commission_with_avg_amount / 100);
        });
    @endphp

    <div class="min-h-screen bg-elm-50">
        {{-- Header + Resumo --}}
        <header class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-elm-100 via-elm-50 to-buttermilk-50"></div>
            <div class="absolute -top-20 -right-24 h-64 w-64 rounded-full bg-buttermilk-200/40 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-elm-200/50 blur-3xl"></div>

            <div class="relative px-4 pt-6 pb-5">
                <div class="flex items-center gap-3">
                    <div
                        class="h-12 w-12 rounded-2xl bg-white shadow-sm ring-1 ring-elm-200/60 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset(main_root() . '/assets/img/logobeee.png') }}" alt="{{ env('APP_NAME') }}"
                            class="h-9 w-9 object-contain">
                    </div>

                    <div class="flex flex-col">
                        <h1 class="text-xl font-extrabold text-elm-900 tracking-tight">Meus Investimentos</h1>
                        <p class="text-sm text-elm-700">Resumo geral + detalhes por ciclo.</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white/90 p-4 ring-1 ring-elm-200/70 shadow-sm">
                        <p class="text-[11px] font-semibold text-elm-700">Ciclos ativos</p>
                        <p class="mt-1 text-2xl font-extrabold text-elm-900">{{ $activeCount }}</p>
                        <p class="mt-1 text-xs text-elm-700">Quantidade de pacotes em andamento</p>
                    </div>

                    <div class="rounded-2xl bg-white/90 p-4 ring-1 ring-elm-200/70 shadow-sm">
                        <p class="text-[11px] font-semibold text-elm-700">Total investido</p>
                        <p class="mt-1 text-2xl font-extrabold text-elm-900">{{ price($totalInvested) }}</p>
                        <p class="mt-1 text-xs text-elm-700">Soma dos pacotes adquiridos</p>
                    </div>

                    <div class="rounded-2xl bg-white/90 p-4 ring-1 ring-elm-200/70 shadow-sm">
                        <p class="text-[11px] font-semibold text-elm-700">Ganhos totais</p>
                        <p class="mt-1 text-2xl font-extrabold text-elm-900">{{ price($totalIncome) }}</p>
                        <p class="mt-1 text-xs text-elm-700">Acumulado recebido até agora</p>
                    </div>

                    <div
                        class="rounded-2xl bg-gradient-to-br from-buttermilk-100 to-buttermilk-50 p-4 ring-1 ring-buttermilk-200/80 shadow-sm">
                        <p class="text-[11px] font-semibold text-buttermilk-800">Renda diária total</p>
                        <p class="mt-1 text-2xl font-extrabold text-buttermilk-900">{{ price($totalDailyIncome) }}</p>
                        <p class="mt-1 text-xs text-buttermilk-900/80">Estimativa somando todos os ciclos</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Lista --}}
        <div class="px-4 pb-28">
            <div class="mt-4 grid gap-4">
                @forelse ($purchases as $purchase)
                    @php
                        $package = $purchase->package;
                        $dailyProfit = $package ? $package->price * ($package->commission_with_avg_amount / 100) : 0;

                        $validityDays = $package?->validity ?? 0;
                        $progress = $validityDays > 0 ? 35 : 0; // troque pela sua regra real (created_at / expires_at)
                    @endphp

                    @php
                        $package = $purchase->package;

                        $startAt = $purchase->created_at; // início do ciclo
                        $endAt = $purchase->validity ? \Carbon\Carbon::parse($purchase->validity) : null; // expiração do ciclo

                        $now = now();

                        $progress = 0;
                        $daysRemaining = 0;
                        $isFinished = false;

                        if ($startAt && $endAt) {
                            // Se já expirou
                            $isFinished = $now->greaterThanOrEqualTo($endAt);

                            // total do ciclo em segundos
                            $totalSeconds = $startAt->diffInSeconds($endAt, false);

                            // tempo passado em segundos
                            $elapsedSeconds = $startAt->diffInSeconds($now, false);

                            // Proteção caso venha inválido (endAt <= startAt)
                            if ($totalSeconds > 0) {
                                $progress = (int) round(($elapsedSeconds / $totalSeconds) * 100);
                            } else {
                                $progress = 100;
                            }

                            // trava entre 0 e 100
                            $progress = max(0, min(100, $progress));

                            // dias restantes (pra exibir)
                            $daysRemaining = $now->lt($endAt) ? $now->diffInDays($endAt) : 0;
                        }
                    @endphp

                    <article class="relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-elm-200/70">
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-elm-500 via-elm-300 to-buttermilk-400">
                        </div>

                        <div class="p-4 flex flex-col gap-4">
                            <div class="flex gap-3">
                                <div class="h-[74px] w-[92px] overflow-hidden rounded-xl bg-elm-100 ring-1 ring-elm-200/60">
                                    @if ($package && $package->photo)
                                        <img src="{{ asset(main_root() . '/' . $package->photo) }}"
                                            alt="{{ $package->name }}" class="h-full w-full object-cover" />
                                    @else
                                        <div
                                            class="h-full w-full flex items-center justify-center text-xs font-semibold text-elm-700">
                                            Sem imagem
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h2 class="truncate text-lg font-extrabold text-elm-900">
                                            {{ $package?->name ?? 'Pacote indisponível' }}
                                        </h2>

                                        <span
                                            class="shrink-0 inline-flex items-center gap-1 rounded-full bg-buttermilk-100 px-2.5 py-1
                                                    text-[11px] font-bold text-buttermilk-800 ring-1 ring-buttermilk-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-buttermilk-500"></span>
                                            Ativo
                                        </span>
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-elm-50 px-2 py-1 text-[11px] font-semibold
                                                    text-elm-800 ring-1 ring-elm-200/70">
                                            Ciclo: {{ $validityDays }} dias
                                        </span>

                                        <span
                                            class="inline-flex items-center rounded-full bg-elm-50 px-2 py-1 text-[11px] font-semibold
                                                    text-elm-800 ring-1 ring-elm-200/70">
                                            Renda diária: <span class="ml-1 text-elm-900">{{ price($dailyProfit) }}</span>
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        <div
                                            class="flex items-center justify-between text-[11px] font-semibold text-elm-700">
                                            <span>
                                                Progresso do ciclo
                                                <span class="font-normal text-elm-600">
                                                    • {{ $daysRemaining }} dia(s) restante(s)
                                                </span>
                                            </span>

                                            <span class="{{ $isFinished ? 'text-buttermilk-800' : 'text-elm-900' }}">
                                                {{ $progress }}%
                                            </span>
                                        </div>

                                        <div
                                            class="mt-1 h-2 w-full rounded-full bg-elm-100 ring-1 ring-elm-200/60 overflow-hidden">
                                            <div class="h-full rounded-full
                {{ $isFinished ? 'bg-gradient-to-r from-buttermilk-400 to-buttermilk-500' : 'bg-gradient-to-r from-elm-500 to-buttermilk-400' }}"
                                                style="width: {{ $progress }}%"></div>
                                        </div>

                                        <div class="mt-1 flex items-center justify-between text-[11px] text-elm-700">
                                            <span>
                                                Início: <span
                                                    class="font-semibold text-elm-900">{{ $startAt?->format('d/m/Y') }}</span>
                                            </span>
                                            <span>
                                                Expira: <span
                                                    class="font-semibold text-elm-900">{{ $endAt?->format('d/m/Y') }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div class="rounded-2xl bg-elm-50 p-3 ring-1 ring-elm-200/70">
                                    <p class="text-[11px] font-semibold text-elm-700">Preço</p>
                                    <p class="mt-1 text-sm font-extrabold text-elm-900">{{ price($package?->price ?? 0) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-elm-50 p-3 ring-1 ring-elm-200/70">
                                    <p class="text-[11px] font-semibold text-elm-700">Renda total</p>
                                    <p class="mt-1 text-sm font-extrabold text-elm-900">
                                        {{ price($package?->total_return_amount ?? 0) }}</p>
                                </div>

                                <div class="rounded-2xl bg-buttermilk-50 p-3 ring-1 ring-buttermilk-200/70">
                                    <p class="text-[11px] font-semibold text-buttermilk-800">Ganhos</p>
                                    <p class="mt-1 text-sm font-extrabold text-buttermilk-900">
                                        {{ price($purchase->total_income) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <div class="text-xs text-elm-700">
                                    <span class="font-semibold text-elm-900">Status:</span> rendendo normalmente
                                </div>

                                <button type="button"
                                    class="btn-package-details animate-pulse-btn inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2
                                           bg-gradient-to-r from-elm-600 to-elm-500 text-white font-extrabold text-sm
                                           shadow-sm hover:from-elm-700 hover:to-elm-600 active:scale-[0.98]
                                           ring-1 ring-elm-700/20"
                                    data-id="{{ $package?->id }}" data-name="{{ $package?->name }}"
                                    data-price="{{ price($package?->price ?? 0) }}"
                                    data-validity="{{ $package?->validity ?? 0 }}" data-photo="{{ $package?->photo }}"
                                    data-daily-return="{{ price($dailyProfit) }}"
                                    data-total-return="{{ price($package?->total_return_amount ?? 0) }}"
                                    data-income="{{ price($purchase->total_income) }}">
                                    Ver detalhes
                                    <span class="rounded-lg bg-white/15 px-2 py-0.5 text-[12px] font-extrabold">
                                        {{ price($purchase->total_income) }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-elm-200/70">
                        <h2 class="text-lg font-extrabold text-elm-900">Você ainda não possui investimentos ativos.</h2>
                        <p class="mt-1 text-sm text-elm-700">Comece agora e veja seus rendimentos crescerem 🚀</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL DETALHES --}}
        <div id="packageModal" class="fixed inset-0 z-50 hidden">
            {{-- Backdrop --}}
            <div id="packageBackdrop" class="absolute inset-0 bg-elm-950/60 backdrop-blur-sm opacity-0"></div>

            {{-- Sheet modal bottom --}}
            <div class="absolute inset-x-0 bottom-20">
                <div id="packageSheet"
                    class="mx-auto w-full max-w-lg rounded-t-3xl bg-white shadow-2xl ring-1 ring-elm-200/70 translate-y-full opacity-0">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-12 w-12 rounded-2xl bg-elm-50 ring-1 ring-elm-200/70 overflow-hidden flex items-center justify-center">
                                    <img id="mPhoto" src="" alt=""
                                        class="h-full w-full object-cover hidden">
                                    <span id="mPhotoFallback" class="text-elm-800 font-extrabold">LA</span>
                                </div>
                                <div class="min-w-0">
                                    <h3 id="mName" class="truncate text-lg font-extrabold text-elm-900">Detalhes</h3>
                                    <p class="text-xs text-elm-700">Informações do seu ciclo</p>
                                </div>
                            </div>

                            <button type="button" id="closePackageModal"
                                class="rounded-xl px-3 py-2 text-sm font-extrabold text-elm-800 bg-elm-50 ring-1 ring-elm-200/70 hover:bg-elm-100">
                                Fechar
                            </button>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <div class="rounded-2xl bg-elm-50 p-3 ring-1 ring-elm-200/70">
                                <p class="text-[11px] font-semibold text-elm-700">Preço</p>
                                <p id="mPrice" class="mt-1 text-sm font-extrabold text-elm-900">—</p>
                            </div>

                            <div class="rounded-2xl bg-elm-50 p-3 ring-1 ring-elm-200/70">
                                <p class="text-[11px] font-semibold text-elm-700">Validade</p>
                                <p id="mValidity" class="mt-1 text-sm font-extrabold text-elm-900">—</p>
                            </div>

                            <div class="rounded-2xl bg-buttermilk-50 p-3 ring-1 ring-buttermilk-200/70">
                                <p class="text-[11px] font-semibold text-buttermilk-800">Renda diária</p>
                                <p id="mDaily" class="mt-1 text-sm font-extrabold text-buttermilk-900">—</p>
                            </div>

                            <div class="rounded-2xl bg-elm-50 p-3 ring-1 ring-elm-200/70">
                                <p class="text-[11px] font-semibold text-elm-700">Renda total</p>
                                <p id="mTotal" class="mt-1 text-sm font-extrabold text-elm-900">—</p>
                            </div>

                            <div
                                class="col-span-2 rounded-2xl bg-gradient-to-r from-elm-600 to-elm-500 p-4 text-white ring-1 ring-elm-700/20">
                                <p class="text-[11px] font-semibold text-white/80">Ganhos acumulados</p>
                                <p id="mIncome" class="mt-1 text-xl font-extrabold">—</p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <div class="text-xs text-elm-700">
                                <span class="font-semibold text-elm-900">Dica:</span> acompanhe diariamente para otimizar
                                seu resultado.
                            </div>
                            <button type="button"
                                class="rounded-xl px-4 py-2 text-sm font-extrabold text-elm-900 bg-buttermilk-100 ring-1 ring-buttermilk-200 hover:bg-buttermilk-200">
                                Central de ajuda
                            </button>
                        </div>
                    </div>

                    <div class="h-2"></div>
                </div>
            </div>
        </div>

        {{-- SCRIPT MODAL --}}
        <script>
            (function() {
                const modal = document.getElementById('packageModal');
                const backdrop = document.getElementById('packageBackdrop');
                const sheet = document.getElementById('packageSheet');
                const closeBtn = document.getElementById('closePackageModal');

                const mName = document.getElementById('mName');
                const mPrice = document.getElementById('mPrice');
                const mValidity = document.getElementById('mValidity');
                const mDaily = document.getElementById('mDaily');
                const mTotal = document.getElementById('mTotal');
                const mIncome = document.getElementById('mIncome');
                const mPhoto = document.getElementById('mPhoto');
                const mPhotoFallback = document.getElementById('mPhotoFallback');

                const openModal = (data) => {
                    // preenche
                    mName.textContent = data.name || 'Detalhes do pacote';
                    mPrice.textContent = data.price || '—';
                    mValidity.textContent = (data.validity ?? '—') + ' dias';
                    mDaily.textContent = data.dailyReturn || '—';
                    mTotal.textContent = data.totalReturn || '—';
                    mIncome.textContent = data.income || '—';

                    const photo = data.photo || '';
                    if (photo) {
                        mPhoto.src = photo;
                        mPhoto.alt = data.name || 'Pacote';
                        mPhoto.classList.remove('hidden');
                        mPhotoFallback.classList.add('hidden');
                    } else {
                        mPhoto.classList.add('hidden');
                        mPhotoFallback.classList.remove('hidden');
                    }

                    // mostra
                    modal.classList.remove('hidden');

                    // animações (enter)
                    backdrop.classList.remove('backdrop-exit');
                    sheet.classList.remove('modal-exit-bottom');

                    backdrop.classList.add('backdrop-enter');
                    sheet.classList.add('modal-enter-bottom');

                    // garante estados base
                    backdrop.classList.remove('opacity-0');
                    sheet.classList.remove('translate-y-full', 'opacity-0');
                };

                const closeModal = () => {
                    // animações (exit)
                    backdrop.classList.remove('backdrop-enter');
                    sheet.classList.remove('modal-enter-bottom');

                    backdrop.classList.add('backdrop-exit');
                    sheet.classList.add('modal-exit-bottom');

                    // depois esconde
                    window.setTimeout(() => {
                        modal.classList.add('hidden');

                        // reseta base
                        backdrop.classList.add('opacity-0');
                        sheet.classList.add('translate-y-full', 'opacity-0');

                        backdrop.classList.remove('backdrop-exit');
                        sheet.classList.remove('modal-exit-bottom');
                    }, 320);
                };

                // abrir
                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('.btn-package-details');
                    if (!btn) return;

                    openModal({
                        id: btn.dataset.id,
                        name: btn.dataset.name,
                        price: btn.dataset.price,
                        validity: btn.dataset.validity,
                        photo: btn.dataset.photo,
                        dailyReturn: btn.dataset.dailyReturn,
                        totalReturn: btn.dataset.totalReturn,
                        income: btn.dataset.income,
                    });
                });

                // fechar
                closeBtn.addEventListener('click', closeModal);
                backdrop.addEventListener('click', closeModal);

                // ESC
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
                });
            })();
        </script>
    </div>
@endsection
