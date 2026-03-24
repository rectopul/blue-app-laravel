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
        <div class="relative">
            <!-- Espaço reservado para o SVG do Ovo -->
            <svg class="w-16 h-16 drop-shadow-xl" viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
                <path d="M50 0C22.3858 0 0 44.7715 0 100C0 111.046 22.3858 120 50 120C77.6142 120 100 111.046 100 100C100 44.7715 77.6142 0 50 0Z" fill="#FBBF24" />
                <path d="M50 10C30 10 15 45 15 90C15 105 30 110 50 110C70 110 85 105 85 90C85 45 70 10 50 10Z" fill="#F59E0B" fill-opacity="0.3" />
            </svg>
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <span class="text-white font-bold text-xs">?</span>
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
