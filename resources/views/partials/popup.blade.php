{{-- Popup WhatsApp Component --}}
<div x-data="{ showPopup: true }" x-show="showPopup" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm"
    @click.self="showPopup = false">

    <div
        class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:scale-[1.02]">

        {{-- Header com gradiente --}}
        <div class="relative bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
            {{-- Botão fechar --}}
            <button @click="showPopup = false"
                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition-all duration-200 hover:rotate-90 group">
                <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            {{-- Título com emoji animado --}}
            <div class="flex items-center space-x-3">
                <span class="text-2xl animate-bounce">👋</span>
                <h2 class="text-xl font-bold">Junte-se ao nosso grupo!</h2>
            </div>
        </div>

        {{-- Conteúdo principal --}}
        <div class="p-6 space-y-6">
            {{-- Logo WhatsApp --}}
            <div class="flex justify-center">
                <div
                    class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow duration-300 hover:scale-110 transform transition-transform">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                    </svg>
                </div>
            </div>

            {{-- Texto descritivo --}}
            <div class="text-center space-y-2">
                <p class="text-gray-700 leading-relaxed">
                    Participe do nosso grupo exclusivo no WhatsApp e fique por dentro das
                    <span class="font-semibold text-green-600">novidades</span>,
                    <span class="font-semibold text-blue-600">promoções</span> e
                    <span class="font-semibold text-purple-600">conteúdos especiais</span>!
                </p>
            </div>

            {{-- Botão de ação --}}
            <div class="space-y-3">
                <a href="https://chat.whatsapp.com/DyoW4ISqCUf7iclkbZ65MY?mode=ac_t" target="_blank"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center space-x-3 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                    </svg>
                    <span>Entrar no Grupo</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                {{-- Link alternativo para fechar --}}
                <button @click="showPopup = false"
                    class="w-full text-gray-500 hover:text-gray-700 text-sm font-medium py-2 transition-colors duration-200 hover:underline">
                    Talvez mais tarde
                </button>
            </div>

            {{-- Indicadores de benefícios --}}
            <div class="flex justify-center space-x-6 pt-2">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span>Grátis</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s"></div>
                    <span>Exclusivo</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                    <span>Sem spam</span>
                </div>
            </div>
        </div>

        {{-- Decoração inferior --}}
        <div class="h-1 bg-gradient-to-r from-green-500 via-blue-500 to-purple-500"></div>
    </div>
</div>

{{-- Estilos adicionais para animações customizadas --}}
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
