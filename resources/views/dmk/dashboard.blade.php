@extends('layouts.dmk')

@section('content')
    <header class="sticky top-0 z-40 w-full bg-white/70 backdrop-blur-md border-b border-slate-100/50">
        <div class="max-w-[600px] mx-auto h-[65px] px-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm border border-slate-100">
                    <img src="{{ asset(main_root() . '/assets/img/logobeee.png') }}" alt="Logo"
                        class="w-full h-full object-fill">
                </div>
                <span class="font-bold text-slate-800 tracking-tight text-lg">{{ env('APP_NAME') }}</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="https://t.me/+Qm95K93C1xhmNzZh"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-50 text-blue-500 transition-transform active:scale-90">
                    <i class="fab fa-telegram text-xl"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="w-full max-w-[600px] mx-auto px-4 pb-24 relative">
        <div class="mt-4 rounded-[28px] overflow-hidden shadow-xl shadow-slate-200/50">
            @include('partials.dmk.slides')
        </div>

        <div class="w-full bg-elm-50/50 border border-elm-100 rounded-2xl py-2 px-4 my-4">
            <div class="flex items-center overflow-hidden">
                <span class="material-symbols-outlined text-elm-600 !text-sm mr-2">campaign</span>
                <div class="flex whitespace-nowrap animate-scroll text-xs font-medium text-elm-700">
                    <span class="pr-12">🎁 Ganhe R$ 10,00 ao se cadastrar agora!</span>
                    <span class="pr-12">🎁 Ganhe R$ 10,00 ao se cadastrar agora!</span>
                </div>
            </div>
        </div>

        <section class="space-y-4">
            @include('partials.dmk.summary')
            @include('partials.dmk.navigation')
        </section>

        <div class="mt-6">
            <h3 class="text-slate-800 font-bold text-lg mb-4 flex items-center gap-2 px-1">
                <span class="w-1.5 h-5 bg-elm-500 rounded-full"></span>
                Planos Disponíveis
            </h3>
            <div class="grid grid-cols-1 gap-4">
                @foreach ($packages as $package)
                    @include('partials.dmk.package_card')
                @endforeach
            </div>
        </div>
    </main>

    {{-- POPUP MIGRAÇÃO TELEGRAM --}}
    <div id="telegramMigrationModal" class="fixed inset-0 z-[70] hidden items-end justify-center">
        <div id="telegramBackdrop"
            class="absolute inset-0 bg-elm-950/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <div id="telegramSheet"
            class="absolute top-0 w-full max-w-[520px] bg-white rounded-t-[32px] shadow-2xl transform translate-y-full opacity-0 transition-all duration-500 ease-out p-6 pt-3">

            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto my-3 cursor-pointer" id="telegramCloseHandle">
            </div>

            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="h-12 w-12 rounded-2xl bg-elm-50 ring-1 ring-elm-200/70 overflow-hidden flex items-center justify-center">
                        <img src="{{ asset(main_root() . '/assets/img/logobeee.png') }}" alt="Logo"
                            class="h-full w-full object-cover">
                    </div>

                    <div class="min-w-0">
                        <h2 class="text-lg font-extrabold text-elm-900 leading-tight">
                            Aviso importante
                        </h2>
                        <p class="text-xs text-elm-700">
                            Comunidade oficial
                        </p>
                    </div>
                </div>

                <button id="telegramCloseBtn"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-elm-50 text-elm-700 ring-1 ring-elm-200/70 active:scale-95">
                    ✕
                </button>
            </div>

            <div class="mt-4 rounded-2xl border border-elm-100 bg-elm-50/50 p-4">
                <p class="text-sm text-elm-900 font-bold mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined !text-[20px] text-buttermilk-600">verified</span>
                    Operações seguem normalmente ✅
                </p>

                <p class="text-sm text-elm-800 leading-relaxed">
                    Devido a <span class="font-semibold">restrições do WhatsApp</span>, migramos nosso grupo oficial
                    para o
                    <span class="font-semibold">Telegram</span>.
                    Para receber avisos, suporte e atualizações, entre no novo grupo pelo botão abaixo.
                </p>
            </div>

            <a href="https://t.me/+Qm95K93C1xhmNzZh" target="_blank" rel="noopener"
                class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-[20px] py-4 px-5
                   bg-gradient-to-r from-elm-600 to-elm-500 text-white font-extrabold
                   shadow-xl shadow-elm-200/50 active:scale-[0.98] transition-all">
                <i class="fab fa-telegram text-xl"></i>
                Entrar no grupo oficial do Telegram
            </a>

            <div class="mt-3 flex items-center justify-between gap-3">
                <button id="telegramDontShow"
                    class="text-xs font-semibold text-elm-700 underline decoration-elm-300 underline-offset-4">
                    Não mostrar novamente
                </button>

                <span class="text-[11px] text-slate-500">
                    Plataforma oficial e segura
                    <span class="inline-block w-1 h-1 rounded-full bg-buttermilk-500 mx-1"></span>
                    {{ env('APP_NAME') }}
                </span>
            </div>

            <div class="h-2"></div>
        </div>
    </div>

    <div id="purchaseModal" class="fixed inset-0 z-[60] flex items-end justify-center hidden">
        <div id="modalBackdrop"
            class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <div id="purchaseModalContent"
            class="relative w-full max-w-[500px] bg-white rounded-t-[32px] shadow-2xl transform translate-y-full transition-all duration-500 ease-out p-6 pt-2">

            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto my-3 cursor-pointer" id="closeHandle"></div>

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Confirmar Plano</h2>
                <button id="closeModal"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500">✕</button>
            </div>

            <div class="space-y-5">
                <div class="relative rounded-2xl overflow-hidden h-44 shadow-lg">
                    <img id="modalPackagePhoto" src="" class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-4 text-white">
                        <h3 id="modalPackageName" class="text-lg font-bold"></h3>
                        <p id="modalPackageValidity" class="text-xs opacity-80 uppercase tracking-widest font-semibold"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Lucro Diário</p>
                        <span id="modalPackageDaily" class="text-lg font-bold text-elm-600"></span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Retorno Total</p>
                        <span id="modalPackageTotal" class="text-lg font-bold text-slate-800"></span>
                    </div>
                </div>

                <div class="py-2 text-center">
                    <span class="text-slate-400 text-sm">Preço do Investimento</span>
                    <p id="modalPackagePrice" class="text-3xl font-black text-elm-600 tracking-tight"></p>
                </div>

                <div id="statusMessage" class="hidden animate-fade-in">
                    <div id="successMessage"
                        class="hidden bg-emerald-50 text-emerald-700 p-4 rounded-2xl border border-emerald-100 flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span class="font-bold">Investimento realizado!</span>
                    </div>
                    <div id="errorMessage" class="hidden bg-red-50 text-red-700 p-4 rounded-2xl border border-red-100">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">error</span>
                            <span class="font-bold" id="errorText"></span>
                        </div>
                    </div>
                </div>

                <button type="button" id="confirmPurchaseBtn"
                    class="w-full bg-slate-900 text-white py-5 rounded-[20px] font-bold shadow-xl shadow-slate-200 active:scale-[0.98] transition-all disabled:bg-slate-300">
                    <span id="btnText">Confirmar Agora</span>
                    <div id="loadingSpinner" class="hidden flex items-center justify-center gap-2">
                        <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span>Processando...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <style>
        .animate-scroll {
            animation: scroll 20s linear infinite;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .tg-enter {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .tg-backdrop-in {
            opacity: 1 !important;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out;
        }
    </style>
@endsection

@push('scripts')
    <script>
        // Mantendo sua lógica JS original, apenas ajustando as classes de animação do modal
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("purchaseModal");
            const modalContent = document.getElementById("purchaseModalContent");
            const modalBackdrop = document.getElementById("modalBackdrop");
            const confirmBtn = document.getElementById("confirmPurchaseBtn");

            // Elementos dinâmicos
            const photo = document.getElementById("modalPackagePhoto");
            const name = document.getElementById("modalPackageName");
            const validity = document.getElementById("modalPackageValidity");
            const daily = document.getElementById("modalPackageDaily");
            const total = document.getElementById("modalPackageTotal");
            const price = document.getElementById("modalPackagePrice");

            // Elementos de status
            const statusMessage = document.getElementById("statusMessage");
            const successMessage = document.getElementById("successMessage");
            const errorMessage = document.getElementById("errorMessage");
            const errorText = document.getElementById("errorText");
            const btnText = document.getElementById("btnText");
            const loadingSpinner = document.getElementById("loadingSpinner");

            let currentPackageId = null;

            // Função para resetar status
            function resetStatus() {
                statusMessage.classList.add("hidden");
                successMessage.classList.add("hidden");
                errorMessage.classList.add("hidden");
                confirmBtn.disabled = false;
                confirmBtn.classList.remove("pulse-green");
                btnText.classList.remove("hidden");
                loadingSpinner.classList.add("hidden");
            }

            // Função para mostrar loading
            function showLoading() {
                confirmBtn.disabled = true;
                btnText.classList.add("hidden");
                loadingSpinner.classList.remove("hidden");
            }

            // Função para mostrar sucesso
            function showSuccess() {
                statusMessage.classList.remove("hidden");
                successMessage.classList.remove("hidden");
                confirmBtn.classList.add("pulse-green", "bg-green-500");
                confirmBtn.classList.remove("from-matisse-500", "to-curious-blue-500");
                btnText.textContent = "Investimento Realizado!";
                btnText.classList.remove("hidden");
                loadingSpinner.classList.add("hidden");

                // Fechar modal automaticamente após 3 segundos
                setTimeout(() => {
                    closeModal();
                    // Opcional: redirecionar para página de sucesso
                    // window.location.href = '/dashboard';
                }, 3000);
            }

            // Função para mostrar erro
            function showError(message) {
                statusMessage.classList.remove("hidden");
                errorMessage.classList.remove("hidden");
                errorText.textContent = message;
                confirmBtn.disabled = false;
                btnText.textContent = "Tentar Novamente";
                btnText.classList.remove("hidden");
                loadingSpinner.classList.add("hidden");
            }

            function openModal() {
                modal.classList.remove("hidden");
                setTimeout(() => {
                    modalBackdrop.classList.replace("opacity-0", "opacity-100");
                    modalContent.classList.replace("translate-y-full", "translate-y-0");
                }, 10);
            }

            function closeModal() {
                modalBackdrop.classList.replace("opacity-100", "opacity-0");
                modalContent.classList.replace("translate-y-0", "translate-y-full");
                setTimeout(() => {
                    modal.classList.add("hidden");
                    // Sua função resetStatus() aqui
                }, 500);
            }

            // Vincule seus botões "btn-investir" à função openModal()
            document.querySelectorAll(".btn-investir").forEach(btn => {
                btn.onclick = () => {
                    // ... (preenchimento dos dados do modal que você já tem)


                    const pkg = btn.dataset;
                    currentPackageId = pkg.id;

                    // Preenche os dados
                    photo.src = pkg.photo;
                    name.textContent = pkg.name;
                    validity.textContent = `Ciclo: ${pkg.validity} dias`;
                    daily.textContent = `${pkg.dailyReturn}`;
                    total.textContent = `${pkg.totalReturn}`;
                    price.textContent =
                        `R$ ${parseFloat(pkg.price).toLocaleString("pt-BR", { minimumFractionDigits: 2 })}`;

                    // Reset status
                    resetStatus();
                    openModal();
                };
            });

            // Confirmação de compra via fetch
            confirmBtn.addEventListener("click", async () => {
                if (!currentPackageId) return;

                showLoading();

                try {
                    const response = await fetch(`/api/purchase/confirmation/${currentPackageId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': "Bearer " + "{{ $token }}",
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            confirmation: true,
                            package_id: currentPackageId
                        })
                    });

                    const data = await response.json();


                    if (response.ok && data.status && data.status === "success") {
                        showSuccess();
                    } else {
                        // Trata erros de validação ou outros erros
                        const errorMsg = data.message ||
                            (data.errors ? Object.values(data.errors).flat().join(', ') :
                                'Erro desconhecido');
                        showError(errorMsg);
                    }
                } catch (error) {
                    console.error('Erro na requisição:', error);
                    showError('Erro de conexão. Verifique sua internet e tente novamente.');
                }
            });

            document.getElementById("closeModal").onclick = closeModal;
            document.getElementById("closeHandle").onclick = closeModal;
            modalBackdrop.onclick = closeModal;
        });

        $('.slidesHomePage').slick({
            infinite: true,
            slidesToShow: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: false,
            dots: true, // Ative os pontos
            fade: true, // O efeito fade é mais elegante para investimentos que o deslize lateral
            cssEase: 'linear'
        });

        // Popup Migração Telegram (com "não mostrar novamente")
        (function() {
            const modal = document.getElementById('telegramMigrationModal');
            const backdrop = document.getElementById('telegramBackdrop');
            const sheet = document.getElementById('telegramSheet');

            const closeBtn = document.getElementById('telegramCloseBtn');
            const closeHandle = document.getElementById('telegramCloseHandle');
            const dontShowBtn = document.getElementById('telegramDontShow');

            const STORAGE_KEY = 'tg_migration_popup_dismissed_v1';

            function openTelegramPopup() {
                if (!modal) return;

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // start states
                backdrop.classList.remove('tg-backdrop-in');
                sheet.classList.remove('tg-enter');

                // animate in
                setTimeout(() => {
                    backdrop.classList.add('tg-backdrop-in');
                    sheet.classList.add('tg-enter');
                }, 10);
            }

            function closeTelegramPopup() {
                if (!modal) return;

                backdrop.classList.remove('tg-backdrop-in');
                sheet.classList.remove('tg-enter');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            // abrir automaticamente (se não dispensado)
            document.addEventListener('DOMContentLoaded', () => {
                const dismissed = localStorage.getItem(STORAGE_KEY);
                if (!dismissed) {
                    // pequeno delay pra não "brigar" com o carregamento inicial
                    setTimeout(openTelegramPopup, 450);
                }
            });

            // fechar
            closeBtn?.addEventListener('click', closeTelegramPopup);
            closeHandle?.addEventListener('click', closeTelegramPopup);
            backdrop?.addEventListener('click', closeTelegramPopup);

            // não mostrar novamente
            dontShowBtn?.addEventListener('click', () => {
                localStorage.setItem(STORAGE_KEY, '1');
                closeTelegramPopup();
            });

            // ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeTelegramPopup();
                }
            });
        })();
    </script>
@endpush
