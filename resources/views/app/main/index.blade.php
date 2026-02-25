@extends('layouts.master')

@section('content')
    <div class="w-[420px] mx-auto bg-white min-h-screen flex flex-col font-sans">

        @include('partials.header')

        <div class="flex gap-4 p-4">
            <button onclick="window.location.href='{{ route('user.deposit') }}'"
                class="flex-1 bg-red-500 text-white py-3 rounded-lg flex items-center justify-center gap-2 shadow-md hover:bg-red-600 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
                Depositar
            </button>
            <button onclick="window.location.href='{{ route('user.withdraw') }}'"
                class="flex-1 border border-red-500 text-red-500 py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-orange-50 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
                Sacar
            </button>
        </div>

        @include('partials.checkin_button')

        <!-- Saldo e Pacotes -->
        <div class="mx-4 p-5 bg-gradient-to-r from-red-400 to-red-600 rounded-xl text-white shadow-lg mb-5">
            <p class="text-sm opacity-80">Saldo disponível</p>
            <h3 class="text-2xl font-bold mt-1">{{ price($user->balance) }}</h3>
            <div class="flex items-center mt-4">
                <div class="flex-1">
                    <p class="text-xs opacity-80">Rendimento semanal</p>
                    <p class="font-semibold">R$ {{ number_format($weeklyEarnings['current_week_earnings'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="flex items-center gap-1 bg-white bg-opacity-20 py-1 px-2 rounded-full">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    @if ($weeklyEarnings['is_positive_growth'])
                        <span
                            class="text-xs font-semibold">{{ number_format($weeklyEarnings['percentage_growth'], 2) }}%</span>
                    @else
                        <span
                            class="text-xs font-semibold">{{ number_format(abs($weeklyEarnings['percentage_growth']), 2) }}%</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-auto p-4 bg-gray-50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Pacotes de Investimento</h2>
                <a href="#"
                    class="text-red-600 text-sm font-medium hover:text-orange-700 flex items-center transition-colors">
                    <span>Ver todos os pacotes</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Pacotes em Destaque -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l.707.707L15.414 5a1 1 0 11-1.414 1.414L13 5.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707L10.586 3A1 1 0 0112 2zm2 8a1 1 0 110 2h5a1 1 0 110 2h-5a1 1 0 110-2h5a1 1 0 110-2h-5z"
                            clip-rule="evenodd" />
                    </svg>
                    Pacotes em Destaque
                </h3>
                <div class="flex flex-col gap-4">
                    @foreach ($featuredPackages as $fpackage)
                        @php
                            $valueProfit = $fpackage->price * ($fpackage->commission_with_avg_amount / 100);
                        @endphp

                        <div
                            class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                            <!-- Cabeçalho do Card -->
                            <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 relative">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center overflow-hidden mr-3 border-2 border-white shadow-sm">
                                        <img src="{{ $fpackage->photo }}" alt="{{ $fpackage->name }}"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white text-lg">{{ $fpackage->name }}</h3>
                                        <span
                                            class="bg-white text-red-600 text-xs font-semibold px-2 py-1 rounded-full inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            Destaque
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Corpo do Card -->
                            <div class="p-4">
                                <!-- Badge de Segurança -->
                                <div class="flex items-center mb-4 text-gray-600 bg-gray-50 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="text-xs font-medium">Investimento verificado e seguro</span>
                                </div>

                                <!-- Informações do Pacote -->
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500 flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Valor do Investimento
                                        </p>
                                        <p class="font-bold text-gray-800 text-lg pl-6">{{ price($fpackage->price) }}</p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-gray-500 flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                            Retorno Total
                                        </p>
                                        <p class="font-bold text-gray-800 text-lg pl-6">
                                            {{ price($valueProfit * $fpackage->validity) }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs text-gray-500 flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Duração
                                            </p>
                                            <p class="font-semibold text-gray-700 pl-6">{{ $fpackage->validity }} dias</p>
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Retorno Diário
                                            </p>
                                            <p class="font-semibold text-green-600 pl-6">
                                                {{ price($valueProfit) }} ao dia</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Confirmação de Compra -->
                            <div x-data="purchaseConfirmation()" x-cloak class="p-4 pt-0">
                                <!-- Botão para abrir o modal - substitui o botão "Investir Agora" no card -->
                                <button
                                    @click="openModal({{ $fpackage->id }}, '{{ $fpackage->name }}', {{ $fpackage->price }})"
                                    class="w-full flex justify-center items-center px-4 py-3 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Investir Agora
                                </button>

                                <!-- Modal - Inspirado no shadcn/ui -->
                                <div x-show="isOpen"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-auto"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                    <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 md:mx-0 overflow-hidden"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="transform scale-95 opacity-0"
                                        x-transition:enter-end="transform scale-100 opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="transform scale-100 opacity-100"
                                        x-transition:leave-end="transform scale-95 opacity-0">

                                        <!-- Cabeçalho do Modal -->
                                        <div class="bg-red-500 p-5 text-white relative">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-xl font-bold flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Confirmar Investimento
                                                </h3>
                                                <button @click="closeModal()"
                                                    class="text-white hover:text-gray-200 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Corpo do Modal -->
                                        <div class="p-6">
                                            <div class="space-y-5">
                                                <!-- Detalhes do investimento -->
                                                <div
                                                    class="flex items-center p-4 bg-orange-50 rounded-lg border border-blue-400">
                                                    <div
                                                        class="flex-shrink-0 bg-white p-3 rounded-full shadow-sm mr-4 border border-blue-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-8 w-8 text-red-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800" x-text="packageName"></h4>
                                                        <div class="flex items-center mt-1">
                                                            <span class="text-lg font-bold text-red-600"
                                                                x-text="'R$ ' + packagePrice"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mensagem de alerta -->
                                                <div
                                                    class="flex items-start p-4 border border-blue-400 bg-blue-50 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-blue-600 mt-0.5 mr-3" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p class="text-sm text-gray-700">
                                                        Ao confirmar esta compra, o valor será debitado do seu saldo e você
                                                        começará a receber os rendimentos diários conforme o período do
                                                        pacote.
                                                    </p>
                                                </div>

                                                <!-- Feedback de erro/sucesso -->
                                                <div x-show="message" x-transition class="p-3 rounded-md"
                                                    :class="messageType === 'error' ?
                                                        'bg-red-50 text-red-800 border border-red-200' :
                                                        'bg-green-50 text-green-800 border border-green-200'">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <template x-if="messageType === 'error'">
                                                                <svg class="h-5 w-5 text-red-500" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="messageType === 'success'">
                                                                <svg class="h-5 w-5 text-green-500" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </template>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm" x-text="message"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rodapé do Modal com botões -->
                                        <div class="border-t border-gray-200 p-4 bg-gray-50 flex justify-end space-x-3">
                                            <button @click="closeModal()"
                                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Cancelar
                                            </button>
                                            <button @click="confirmPurchase()"
                                                :class="{
                                                    'opacity-50 cursor-not-allowed': isLoading,
                                                    'hover:bg-red-600': !isLoading
                                                }"
                                                :disabled="isLoading"
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <span x-show="!isLoading">Confirmar Compra</span>
                                                <span x-show="isLoading" class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    Processando...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Outros Pacotes -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Outros Pacotes
                </h3>
                <div class="flex flex-col gap-4">
                    @foreach ($packages as $package)
                        @php
                            $valueProfit = $package->price * ($package->commission_with_avg_amount / 100);
                        @endphp
                        <div
                            class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                            <!-- Cabeçalho do Card -->
                            <div class="bg-blue-400 p-4 relative">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center overflow-hidden mr-3 border-2 border-white shadow-sm">
                                        <img src="{{ $package->photo }}" alt="{{ $package->name }}"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg">{{ $package->name }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Corpo do Card -->
                            <div class="p-4">
                                <!-- Badge de Segurança -->
                                <div class="flex items-center mb-4 text-gray-600 bg-gray-50 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="text-xs font-medium">Investimento verificado e seguro</span>
                                </div>

                                <!-- Informações do Pacote -->
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500 flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Valor do Investimento
                                        </p>
                                        <p class="font-bold text-gray-800 text-lg pl-6">{{ price($package->price) }}</p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-gray-500 flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                            Retorno Total
                                        </p>
                                        <p class="font-bold text-gray-800 text-lg pl-6">
                                            {{ price($valueProfit * $package->validity) }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-xs text-gray-500 flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Duração
                                            </p>
                                            <p class="font-semibold text-gray-700 pl-6">{{ $package->validity }} dias</p>
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Retorno Diário
                                            </p>
                                            <p class="font-semibold text-green-600 pl-6">
                                                {{ price($valueProfit) }} ao dia</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Confirmação de Compra -->
                            <div x-data="purchaseConfirmation()" x-cloak class="p-4 pt-0">
                                <!-- Botão para abrir o modal - substitui o botão "Investir Agora" no card -->
                                <button
                                    @click="openModal({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})"
                                    class="w-full flex justify-center items-center px-4 py-3 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Investir Agora
                                </button>

                                <!-- Modal - Inspirado no shadcn/ui -->
                                <div x-show="isOpen"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-auto"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                    <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 md:mx-0 overflow-hidden"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="transform scale-95 opacity-0"
                                        x-transition:enter-end="transform scale-100 opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="transform scale-100 opacity-100"
                                        x-transition:leave-end="transform scale-95 opacity-0">

                                        <!-- Cabeçalho do Modal -->
                                        <div class="bg-red-500 p-5 text-white relative">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-xl font-bold flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Confirmar Investimento
                                                </h3>
                                                <button @click="closeModal()"
                                                    class="text-white hover:text-gray-200 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Corpo do Modal -->
                                        <div class="p-6">
                                            <div class="space-y-5">
                                                <!-- Detalhes do investimento -->
                                                <div
                                                    class="flex items-center p-4 bg-orange-50 rounded-lg border border-blue-400">
                                                    <div
                                                        class="flex-shrink-0 bg-white p-3 rounded-full shadow-sm mr-4 border border-blue-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-8 w-8 text-red-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800" x-text="packageName"></h4>
                                                        <div class="flex items-center mt-1">
                                                            <span class="text-lg font-bold text-red-600"
                                                                x-text="'R$ ' + packagePrice"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mensagem de alerta -->
                                                <div
                                                    class="flex items-start p-4 border border-blue-400 bg-blue-50 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-blue-600 mt-0.5 mr-3" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p class="text-sm text-gray-700">
                                                        Ao confirmar esta compra, o valor será debitado do seu saldo e você
                                                        começará a receber os rendimentos diários conforme o período do
                                                        pacote.
                                                    </p>
                                                </div>

                                                <!-- Feedback de erro/sucesso -->
                                                <div x-show="message" x-transition class="p-3 rounded-md"
                                                    :class="messageType === 'error' ?
                                                        'bg-red-50 text-red-800 border border-red-200' :
                                                        'bg-green-50 text-green-800 border border-green-200'">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <template x-if="messageType === 'error'">
                                                                <svg class="h-5 w-5 text-red-500" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="messageType === 'success'">
                                                                <svg class="h-5 w-5 text-green-500" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </template>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm" x-text="message"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rodapé do Modal com botões -->
                                        <div class="border-t border-gray-200 p-4 bg-gray-50 flex justify-end space-x-3">
                                            <button @click="closeModal()"
                                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Cancelar
                                            </button>
                                            <button @click="confirmPurchase()"
                                                :class="{
                                                    'opacity-50 cursor-not-allowed': isLoading,
                                                    'hover:bg-red-600': !isLoading
                                                }"
                                                :disabled="isLoading"
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <span x-show="!isLoading">Confirmar Compra</span>
                                                <span x-show="isLoading" class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    Processando...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('purchaseConfirmation', () => ({
                isOpen: false,
                packageId: null,
                packageName: '',
                packagePrice: 0,
                isLoading: false,
                message: '',
                messageType: 'error',

                openModal(id, name, price) {
                    this.packageId = id;
                    this.packageName = name;
                    this.packagePrice = price;
                    this.message = '';
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden'; // Previne rolagem
                },

                closeModal() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                },

                confirmPurchase() {

                    this.isLoading = true;
                    this.message = '';
                    const userToken = "{{ $token }}";

                    // Fazendo a requisição AJAX para a API
                    fetch('/api/user/purchase', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${userToken}`,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                package_id: this.packageId,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.isLoading = false;

                            console.log('Retorno da compra', data);
                            if (data.status === false) {
                                this.message = data.message;
                                this.messageType = 'error';
                            } else {
                                this.message =
                                    'Parabéns! Seu investimento foi realizado com sucesso.';
                                this.messageType = 'success';

                                // Redirecionar após 2 segundos
                                setTimeout(() => {
                                    // window.location.reload();
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            this.isLoading = false;
                            this.message =
                                'Ocorreu um erro ao processar sua solicitação. Tente novamente.';
                            this.messageType = 'error';
                            console.error('Erro:', error);
                        });
                }
            }));
        });
    </script>
@endsection
