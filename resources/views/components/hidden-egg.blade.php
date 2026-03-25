@if(isset($activeEgg) && $activeEgg)
<div
    x-data="hiddenEgg()"
    x-show="!collected"
    class="fixed z-50 transition-all duration-500 transform hover:scale-110"
    style="bottom: 20%; right: 10%;"
>
    <button
        @click="collect()"
        class="animate-bounce focus:outline-none"
        title="Clique para coletar seu bônus!"
    >
        <div class="relative group">
            <div class="absolute inset-0 bg-pink-400 rounded-full blur-xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
            <!-- Ovo de Páscoa Colorido -->
            <svg class="w-20 h-24 drop-shadow-2xl relative" viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="eggGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#FFD9E5;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#FF80A6;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <path d="M50 0C22.3858 0 0 44.7715 0 100C0 111.046 22.3858 120 50 120C77.6142 120 100 111.046 100 100C100 44.7715 77.6142 0 50 0Z" fill="url(#eggGrad)" />
                <!-- Detalhes decorativos do ovo -->
                <path d="M10 80 Q 50 60 90 80" stroke="white" stroke-width="4" fill="none" opacity="0.4" />
                <path d="M15 95 Q 50 75 85 95" stroke="white" stroke-width="3" fill="none" opacity="0.3" />
                <circle cx="50" cy="40" r="8" fill="white" opacity="0.2" />
            </svg>
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <span class="text-white font-black text-lg drop-shadow-md">🎁</span>
            </div>
        </div>
    </button>
</div>

<script>
function hiddenEgg() {
    return {
        collected: false,
        collect() {
            if (this.collected) return;

            axios.post('{{ route('gamification.collect', $activeEgg->id) }}')
                .then(response => {
                    this.collected = true;
                    // Você pode usar o snakbar do projeto ou um alert simples
                    if (window.showToast) {
                        window.showToast(response.data.message, 'success');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.success(response.data.message);
                    } else {
                        alert(response.data.message);
                    }
                    // Opcional: recarregar saldo visualmente ou a página
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                })
                .catch(error => {
                    const message = error.response?.data?.message || 'Erro ao coletar o bônus.';
                    if (window.showToast) {
                        window.showToast(message, 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error(message);
                    } else {
                        alert(message);
                    }
                });
        }
    }
}
</script>
@endif
