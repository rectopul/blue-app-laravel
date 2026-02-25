<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depósito - Sistema de Investimento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        .deposit-amount-btn {
            transition: all 0.2s ease;
        }

        .deposit-amount-btn.active {
            background-color: #dc2626;
            color: white;
            transform: scale(0.98);
        }

        .input-focus-effect:focus-within {
            box-shadow: 0 0 0 2px #f97316;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <button onclick="window.location.href='{{ route('dashboard') }}'"
                        class="flex items-center text-gray-600 hover:text-red-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">Voltar</span>
                    </button>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Depósito</h1>
                <div class="w-8"></div>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 py-8">
        <!-- Logo e cabeçalho -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Realizar Depósito</h2>
            <p class="text-gray-600 mt-1">Selecione ou digite o valor desejado</p>
        </div>

        <!-- Valores pré-definidos -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-4 gap-2 mb-2">
                <button onclick="getAmount(this, 40)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$40</button>
                <button onclick="getAmount(this, 80)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$80</button>
                <button onclick="getAmount(this, 120)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$120</button>
                <button onclick="getAmount(this, 200)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$200</button>
            </div>
            <div class="grid grid-cols-4 gap-2">
                <button onclick="getAmount(this, 400)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$400</button>
                <button onclick="getAmount(this, 800)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$800</button>
                <button onclick="getAmount(this, 1000)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$1000</button>
                <button onclick="getAmount(this, 1200)"
                    class="deposit-amount-btn py-3 text-center rounded-lg border border-gray-200 hover:border-red-500 font-medium text-gray-700 hover:bg-red-500 transition-all">R$1200</button>
            </div>
        </div>

        <!-- Campo de valor -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Valor de Depósito</label>
            <div class="flex items-center input-focus-effect rounded-lg border border-gray-200 overflow-hidden">
                <input type="text" name="amount" id="amount"
                    class="w-full py-3 px-4 outline-none text-gray-800 text-lg" placeholder="Digite o valor">
                <div class="px-4 text-gray-500 font-medium">{{ currency() }}</div>
            </div>
        </div>

        <!-- Método de pagamento (hidden select) -->
        <select name="channel" id="channel" class="hidden">
            @foreach (\App\Models\PaymentMethod::get() as $element)
                <option value="{{ $element->name }}" {{ $element->name === 'PIX' ? 'selected' : '' }}>
                    {{ $element->name }}
                </option>
            @endforeach
        </select>

        <!-- Botão de confirmação -->
        <button onclick="payment()"
            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-xl shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd" />
            </svg>
            Confirmar Depósito
        </button>

        <!-- Informações de segurança -->
        <div class="mt-6 p-4 bg-white rounded-xl shadow-sm">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1v-3a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="font-medium text-gray-800">Informações importantes</h3>
            </div>
            <ul class="space-y-2 text-gray-600 pl-11">
                <li class="flex items-start">
                    <span
                        class="inline-flex items-center justify-center w-5 h-5 bg-primary-100 rounded-full text-red-600 font-semibold text-xs mr-2 mt-0.5">1</span>
                    <span>Depósito mínimo de R$10,00</span>
                </li>
                <li class="flex items-start">
                    <span
                        class="inline-flex items-center justify-center w-5 h-5 bg-primary-100 rounded-full text-red-600 font-semibold text-xs mr-2 mt-0.5">2</span>
                    <span>Você pode depositar em qualquer hora do dia!</span>
                </li>
                <li class="flex items-start">
                    <span
                        class="inline-flex items-center justify-center w-5 h-5 bg-primary-100 rounded-full text-red-600 font-semibold text-xs mr-2 mt-0.5">3</span>
                    <span>Transferências são processadas com segurança e criptografia avançada</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Modal PIX -->
    <div id="pixModal" class="fixed inset-0 z-50 hidden">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closePixModal()">
        </div>

        <!-- Modal Content -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all w-full max-w-md">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">PIX Gerado</h3>
                                    <p class="text-primary-100 text-sm">Realize o pagamento</p>
                                </div>
                            </div>
                            <button onclick="closePixModal()"
                                class="text-white hover:text-primary-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <!-- Valor -->
                        <div class="text-center mb-6">
                            <p class="text-gray-600 text-sm mb-1">Valor do depósito</p>
                            <p class="text-3xl font-bold text-gray-800" id="modalAmount">R$ 0,00</p>
                        </div>

                        <!-- QR Code -->
                        <div class="bg-white border-2 border-gray-100 rounded-xl p-4 mb-6">
                            <div class="text-center">
                                <div class="inline-block p-4 bg-white rounded-lg shadow-sm">
                                    <div id="qrCodeImage">

                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mt-3">Escaneie o código com seu banco</p>
                            </div>
                        </div>

                        <!-- Código PIX -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-700">Código PIX Copia e Cola</label>
                                <button onclick="copyPixCode()" id="copyButton"
                                    class="flex items-center text-red-600 hover:text-primary-700 text-sm font-medium transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span id="copyText">Copiar</span>
                                </button>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-3">
                                <code id="pixCode" class="text-xs text-gray-700 break-all font-mono"></code>
                            </div>
                        </div>

                        <!-- Instruções -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1v-3a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800 mb-1">Como pagar:</h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• Escaneie o QR Code com seu app do banco</li>
                                        <li>• Ou copie e cole o código PIX</li>
                                        <li>• O pagamento é processado instantaneamente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Timer -->
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 mr-2"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-amber-800 font-medium">PIX válido por: <span id="timer"
                                        class="font-bold">15:00</span></span>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex space-x-3">
                            <button onclick="copyPixCode()"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copiar Código
                            </button>
                            <button onclick="closePixModal()"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="van-toast--loading fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        style="display: none;">
        <div class="bg-white p-6 rounded-xl flex flex-col items-center shadow-2xl">
            <div class="w-12 h-12 border-4 border-red-500 border-t-transparent rounded-full animate-spin mb-4">
            </div>
            <p class="text-gray-700 font-medium">Gerando PIX...</p>
            <p class="text-gray-500 text-sm mt-1">Aguarde um momento</p>
        </div>
    </div>

    @include('alert-message')

    <style>
        *,
        ::before,
        ::after {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-gradient-from-position: ;
            --tw-gradient-via-position: ;
            --tw-gradient-to-position: ;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
            --tw-contain-size: ;
            --tw-contain-layout: ;
            --tw-contain-paint: ;
            --tw-contain-style:
        }

        ::backdrop {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-gradient-from-position: ;
            --tw-gradient-via-position: ;
            --tw-gradient-to-position: ;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
            --tw-contain-size: ;
            --tw-contain-layout: ;
            --tw-contain-paint: ;
            --tw-contain-style:
        }

        /* ! tailwindcss v3.4.16 | MIT License | https://tailwindcss.com */
        *,
        ::after,
        ::before {
            box-sizing: border-box;
            border-width: 0;
            border-style: solid;
            border-color: #e5e7eb
        }

        ::after,
        ::before {
            --tw-content: ''
        }

        :host,
        html {
            line-height: 1.5;
            -webkit-text-size-adjust: 100%;
            -moz-tab-size: 4;
            tab-size: 4;
            font-family: Open Sans, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-feature-settings: normal;
            font-variation-settings: normal;
            -webkit-tap-highlight-color: transparent
        }

        body {
            margin: 0;
            line-height: inherit
        }

        hr {
            height: 0;
            color: inherit;
            border-top-width: 1px
        }

        abbr:where([title]) {
            -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-size: inherit;
            font-weight: inherit
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        b,
        strong {
            font-weight: bolder
        }

        code,
        kbd,
        pre,
        samp {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-feature-settings: normal;
            font-variation-settings: normal;
            font-size: 1em
        }

        small {
            font-size: 80%
        }

        sub,
        sup {
            font-size: 75%;
            line-height: 0;
            position: relative;
            vertical-align: baseline
        }

        sub {
            bottom: -.25em
        }

        sup {
            top: -.5em
        }

        table {
            text-indent: 0;
            border-color: inherit;
            border-collapse: collapse
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            font-family: inherit;
            font-feature-settings: inherit;
            font-variation-settings: inherit;
            font-size: 100%;
            font-weight: inherit;
            line-height: inherit;
            letter-spacing: inherit;
            color: inherit;
            margin: 0;
            padding: 0
        }

        button,
        select {
            text-transform: none
        }

        button,
        input:where([type=button]),
        input:where([type=reset]),
        input:where([type=submit]) {
            -webkit-appearance: button;
            background-color: transparent;
            background-image: none
        }

        :-moz-focusring {
            outline: auto
        }

        :-moz-ui-invalid {
            box-shadow: none
        }

        progress {
            vertical-align: baseline
        }

        ::-webkit-inner-spin-button,
        ::-webkit-outer-spin-button {
            height: auto
        }

        [type=search] {
            -webkit-appearance: textfield;
            outline-offset: -2px
        }

        ::-webkit-search-decoration {
            -webkit-appearance: none
        }

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            font: inherit
        }

        summary {
            display: list-item
        }

        blockquote,
        dd,
        dl,
        figure,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        hr,
        p,
        pre {
            margin: 0
        }

        fieldset {
            margin: 0;
            padding: 0
        }

        legend {
            padding: 0
        }

        menu,
        ol,
        ul {
            list-style: none;
            margin: 0;
            padding: 0
        }

        dialog {
            padding: 0
        }

        textarea {
            resize: vertical
        }

        input::placeholder,
        textarea::placeholder {
            opacity: 1;
            color: #9ca3af
        }

        [role=button],
        button {
            cursor: pointer
        }

        :disabled {
            cursor: default
        }

        audio,
        canvas,
        embed,
        iframe,
        img,
        object,
        svg,
        video {
            display: block;
            vertical-align: middle
        }

        img,
        video {
            max-width: 100%;
            height: auto
        }

        [hidden]:where(:not([hidden=until-found])) {
            display: none
        }

        .fixed {
            position: fixed
        }

        .relative {
            position: relative
        }

        .inset-0 {
            inset: 0px
        }

        .z-10 {
            z-index: 10
        }

        .z-50 {
            z-index: 50
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        .mb-1 {
            margin-bottom: 4px
        }

        .mb-2 {
            margin-bottom: 8px
        }

        .mb-3 {
            margin-bottom: 12px
        }

        .mb-4 {
            margin-bottom: 16px
        }

        .mb-6 {
            margin-bottom: 24px
        }

        .mb-8 {
            margin-bottom: 32px
        }

        .ml-3 {
            margin-left: 12px
        }

        .mr-1 {
            margin-right: 4px
        }

        .mr-2 {
            margin-right: 8px
        }

        .mr-3 {
            margin-right: 12px
        }

        .mt-0\.5 {
            margin-top: 2px
        }

        .mt-1 {
            margin-top: 4px
        }

        .mt-3 {
            margin-top: 12px
        }

        .mt-6 {
            margin-top: 24px
        }

        .block {
            display: block
        }

        .inline-block {
            display: inline-block
        }

        .flex {
            display: flex
        }

        .inline-flex {
            display: inline-flex
        }

        .grid {
            display: grid
        }

        .hidden {
            display: none
        }

        .h-10 {
            height: 40px
        }

        .h-12 {
            height: 48px
        }

        .h-16 {
            height: 64px
        }

        .h-20 {
            height: 80px
        }

        .h-4 {
            height: 16px
        }

        .h-5 {
            height: 20px
        }

        .h-6 {
            height: 24px
        }

        .h-8 {
            height: 32px
        }

        .min-h-full {
            min-height: 100%
        }

        .min-h-screen {
            min-height: 100vh
        }

        .w-10 {
            width: 40px
        }

        .w-12 {
            width: 48px
        }

        .w-20 {
            width: 80px
        }

        .w-4 {
            width: 16px
        }

        .w-5 {
            width: 20px
        }

        .w-6 {
            width: 24px
        }

        .w-8 {
            width: 32px
        }

        .w-full {
            width: 100%
        }

        .max-w-7xl {
            max-width: 80rem
        }

        .max-w-md {
            max-width: 28rem
        }

        .flex-1 {
            flex: 1 1 0%
        }

        .flex-shrink-0 {
            flex-shrink: 0
        }

        .transform {
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr))
        }

        .flex-col {
            flex-direction: column
        }

        .items-start {
            align-items: flex-start
        }

        .items-center {
            align-items: center
        }

        .justify-center {
            justify-content: center
        }

        .justify-between {
            justify-content: space-between
        }

        .gap-2 {
            gap: 8px
        }

        .space-x-3> :not([hidden])~ :not([hidden]) {
            --tw-space-x-reverse: 0;
            margin-right: calc(12px * var(--tw-space-x-reverse));
            margin-left: calc(12px * calc(1 - var(--tw-space-x-reverse)))
        }

        .space-y-1> :not([hidden])~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(4px * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(4px * var(--tw-space-y-reverse))
        }

        .space-y-2> :not([hidden])~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(8px * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(8px * var(--tw-space-y-reverse))
        }

        .overflow-hidden {
            overflow: hidden
        }

        .overflow-y-auto {
            overflow-y: auto
        }

        .break-all {
            word-break: break-all
        }

        .rounded-2xl {
            border-radius: 48px
        }

        .rounded-full {
            border-radius: 9999px
        }

        .rounded-lg {
            border-radius: 24px
        }

        .rounded-xl {
            border-radius: 36px
        }

        .border {
            border-width: 1px
        }

        .border-2 {
            border-width: 2px
        }

        .border-4 {
            border-width: 4px
        }

        .border-amber-200 {
            --tw-border-opacity: 1;
            border-color: rgb(253 230 138 / var(--tw-border-opacity, 1))
        }

        .border-blue-200 {
            --tw-border-opacity: 1;
            border-color: rgb(191 219 254 / var(--tw-border-opacity, 1))
        }

        .border-gray-100 {
            --tw-border-opacity: 1;
            border-color: rgb(243 244 246 / var(--tw-border-opacity, 1))
        }

        .border-gray-200 {
            --tw-border-opacity: 1;
            border-color: rgb(229 231 235 / var(--tw-border-opacity, 1))
        }

        .border-red-500 {
            --tw-border-opacity: 1;
            border-color: rgb(239 68 68 / var(--tw-border-opacity, 1))
        }

        .border-t-transparent {
            border-top-color: transparent
        }

        .bg-amber-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(255 251 235 / var(--tw-bg-opacity, 1))
        }

        .bg-black {
            --tw-bg-opacity: 1;
            background-color: rgb(0 0 0 / var(--tw-bg-opacity, 1))
        }

        .bg-blue-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(239 246 255 / var(--tw-bg-opacity, 1))
        }

        .bg-gray-100 {
            --tw-bg-opacity: 1;
            background-color: rgb(243 244 246 / var(--tw-bg-opacity, 1))
        }

        .bg-gray-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(249 250 251 / var(--tw-bg-opacity, 1))
        }

        .bg-primary-100 {
            --tw-bg-opacity: 1;
            background-color: rgb(233 229 255 / var(--tw-bg-opacity, 1))
        }

        .bg-red-500 {
            --tw-bg-opacity: 1;
            background-color: rgb(239 68 68 / var(--tw-bg-opacity, 1))
        }

        .bg-white {
            --tw-bg-opacity: 1;
            background-color: rgb(255 255 255 / var(--tw-bg-opacity, 1))
        }

        .bg-opacity-20 {
            --tw-bg-opacity: 0.2
        }

        .bg-opacity-50 {
            --tw-bg-opacity: 0.5
        }

        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops))
        }

        .from-red-500 {
            --tw-gradient-from: #ef4444 var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(239 68 68 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
        }

        .to-red-600 {
            --tw-gradient-to: #dc2626 var(--tw-gradient-to-position)
        }

        .p-3 {
            padding: 12px
        }

        .p-4 {
            padding: 16px
        }

        .p-6 {
            padding: 24px
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px
        }

        .px-6 {
            padding-left: 24px;
            padding-right: 24px
        }

        .py-3 {
            padding-top: 12px;
            padding-bottom: 12px
        }

        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px
        }

        .py-6 {
            padding-top: 24px;
            padding-bottom: 24px
        }

        .py-8 {
            padding-top: 32px;
            padding-bottom: 32px
        }

        .pl-11 {
            padding-left: 44px
        }

        .text-center {
            text-align: center
        }

        .text-2xl {
            font-size: 24px;
            line-height: 31.200000000000003px
        }

        .text-3xl {
            font-size: 30px;
            line-height: 36px
        }

        .text-lg {
            font-size: 18px;
            line-height: 27px
        }

        .text-sm {
            font-size: 14px;
            line-height: 21px
        }

        .text-xl {
            font-size: 20px;
            line-height: 28px
        }

        .text-xs {
            font-size: 12px;
            line-height: 19.200000000000003px
        }

        .font-bold {
            font-weight: 700
        }

        .font-medium {
            font-weight: 500
        }

        .font-semibold {
            font-weight: 600
        }

        .text-amber-600 {
            --tw-text-opacity: 1;
            color: rgb(217 119 6 / var(--tw-text-opacity, 1))
        }

        .text-amber-800 {
            --tw-text-opacity: 1;
            color: rgb(146 64 14 / var(--tw-text-opacity, 1))
        }

        .text-blue-600 {
            --tw-text-opacity: 1;
            color: rgb(37 99 235 / var(--tw-text-opacity, 1))
        }

        .text-blue-700 {
            --tw-text-opacity: 1;
            color: rgb(29 78 216 / var(--tw-text-opacity, 1))
        }

        .text-blue-800 {
            --tw-text-opacity: 1;
            color: rgb(30 64 175 / var(--tw-text-opacity, 1))
        }

        .text-gray-500 {
            --tw-text-opacity: 1;
            color: rgb(107 114 128 / var(--tw-text-opacity, 1))
        }

        .text-gray-600 {
            --tw-text-opacity: 1;
            color: rgb(75 85 99 / var(--tw-text-opacity, 1))
        }

        .text-gray-700 {
            --tw-text-opacity: 1;
            color: rgb(55 65 81 / var(--tw-text-opacity, 1))
        }

        .text-gray-800 {
            --tw-text-opacity: 1;
            color: rgb(31 41 55 / var(--tw-text-opacity, 1))
        }

        .text-primary-100 {
            --tw-text-opacity: 1;
            color: rgb(233 229 255 / var(--tw-text-opacity, 1))
        }

        .text-red-600 {
            --tw-text-opacity: 1;
            color: rgb(220 38 38 / var(--tw-text-opacity, 1))
        }

        .text-white {
            --tw-text-opacity: 1;
            color: rgb(255 255 255 / var(--tw-text-opacity, 1))
        }

        .shadow-2xl {
            --tw-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --tw-shadow-colored: 0 25px 50px -12px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        .shadow-sm {
            --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        .outline-none {
            outline: 2px solid transparent;
            outline-offset: 2px
        }

        .backdrop-blur-sm {
            --tw-backdrop-blur: blur(4px);
            -webkit-backdrop-filter: var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia);
            backdrop-filter: var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia)
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        .transition-colors {
            transition-property: color, background-color, border-color, fill, stroke, -webkit-text-decoration-color;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, -webkit-text-decoration-color;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        .transition-opacity {
            transition-property: opacity;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        .duration-200 {
            transition-duration: 200ms
        }

        .hover\:border-red-500:hover {
            --tw-border-opacity: 1;
            border-color: rgb(239 68 68 / var(--tw-border-opacity, 1))
        }

        .hover\:bg-gray-200:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(229 231 235 / var(--tw-bg-opacity, 1))
        }

        .hover\:bg-red-500:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(239 68 68 / var(--tw-bg-opacity, 1))
        }

        .hover\:bg-red-600:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(220 38 38 / var(--tw-bg-opacity, 1))
        }

        .hover\:text-primary-100:hover {
            --tw-text-opacity: 1;
            color: rgb(233 229 255 / var(--tw-text-opacity, 1))
        }

        .hover\:text-primary-700:hover {
            --tw-text-opacity: 1;
            color: rgb(97 27 248 / var(--tw-text-opacity, 1))
        }

        .hover\:text-red-600:hover {
            --tw-text-opacity: 1;
            color: rgb(220 38 38 / var(--tw-text-opacity, 1))
        }

        .hover\:shadow:hover {
            --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }
    </style>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        let timerInterval;

        window.onload = function() {
            document.querySelector('.van-toast--loading').style.display = 'none';
        };

        function getAmount(_this, amount) {
            const buttons = document.querySelectorAll('.deposit-amount-btn');
            buttons.forEach(button => {
                button.classList.remove('active');
            });

            _this.classList.add('active');
            document.querySelector('input[name="amount"]').value = amount;
        }

        function generateTrxId(prefix = 'TRX') {
            const timestamp = Date.now().toString(36);
            const random = Math.random().toString(36).substr(2, 6).toUpperCase();
            return `${prefix}-${timestamp}-${random}`;
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(amount);
        }

        function showPixModal(data) {
            // Preencher dados do modal
            document.getElementById('modalAmount').textContent = formatCurrency(data.amount);
            // document.getElementById('qrCodeImage').src = `data:image/png;base64,${data.paymentCodeBase64}`;
            document.getElementById('pixCode').textContent = data.paymentCode;
            const qrCodeDiv = document.getElementById("qrCodeImage");

            const pixCode = data.paymentCode

            QRCode.toCanvas(pixCode, {
                errorCorrectionLevel: 'H'
            }, function(err, canvas) {
                if (err) {
                    console.error("Erro ao gerar QR Code:", err);
                    return;
                }
                qrCodeDiv.appendChild(canvas);
            });

            // Mostrar modal
            document.getElementById('pixModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Iniciar timer
            startTimer(15 * 60); // 15 minutos
        }

        function closePixModal() {
            document.getElementById('pixModal').classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Parar timer
            if (timerInterval) {
                clearInterval(timerInterval);
            }
        }

        function startTimer(seconds) {
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            const timerElement = document.getElementById('timer');

            timerInterval = setInterval(() => {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;

                timerElement.textContent =
                    `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;

                if (seconds <= 0) {
                    clearInterval(timerInterval);
                    timerElement.textContent = "Expirado";
                    timerElement.classList.add('text-red-600');
                }

                seconds--;
            }, 1000);
        }

        async function copyPixCode() {
            const pixCode = document.getElementById('pixCode').textContent;
            const copyButton = document.getElementById('copyButton');
            const copyText = document.getElementById('copyText');

            try {
                await navigator.clipboard.writeText(pixCode);

                // Feedback visual
                copyText.textContent = 'Copiado!';
                copyButton.classList.remove('text-red-600');
                copyButton.classList.add('text-green-600');

                setTimeout(() => {
                    copyText.textContent = 'Copiar';
                    copyButton.classList.remove('text-green-600');
                    copyButton.classList.add('text-red-600');
                }, 2000);

            } catch (err) {
                console.error('Erro ao copiar:', err);
                // Fallback para navegadores mais antigos
                const textArea = document.createElement('textarea');
                textArea.value = pixCode;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                copyText.textContent = 'Copiado!';
                setTimeout(() => {
                    copyText.textContent = 'Copiar';
                }, 2000);
            }
        }

        async function payment() {
            const amount = document.querySelector('input[name="amount"]').value;

            try {
                if (amount >= 10) {
                    document.querySelector('.van-toast--loading').style.display = 'block';
                    const channel = document.querySelector('select[name="channel"]').value;
                    const token = "{{ $token }}"

                    const options = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({
                            amount: amount,
                            transaction_id: generateTrxId(),
                        })
                    }

                    const response = await fetch("{{ route('api.deposit.store') }}", options);

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Erro ao processar o depósito');
                    }

                    const data = await response.json();
                    console.log("Resposta da API:", data);

                    // Fechar loading e mostrar modal PIX
                    document.querySelector('.van-toast--loading').style.display = 'none';
                    showPixModal(data.data);

                } else {
                    message('Valor mínimo: R$10,00.');
                }
            } catch (error) {
                console.error('Erro ao processar o depósito:', error);
                message('Ocorreu um erro ao processar o depósito. Tente novamente mais tarde.');
            } finally {
                document.querySelector('.van-toast--loading').style.display = 'none';
            }
        }

        // Fechar modal com ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePixModal();
            }
        });
    </script>
    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
</body>

</html>
