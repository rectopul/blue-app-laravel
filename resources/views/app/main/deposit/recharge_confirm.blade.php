<html
    style="--vh: 7.38px; --primary: #41CEB2; --primary-second: #00C1CC; --bg: linear-gradient(180deg, #004b50 0%, #011f21 100%); --bg-tab: #344e4f; --bg-input: rgba(0, 52, 55, 0.29); --btn-bg: linear-gradient(144deg, #00C1CC 14.85%, #00828A 83.66%); --btn-bg2: linear-gradient(144deg, #47E7DD 14.85%, #00B68A 83.66%); --radio-color: #fff; --btn-shadow: 2px 6px 10px 0px rgba(65,206,178, 0.4);">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="google" content="notranslate">

    <title>{{env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{asset('public/d')}}/BaseMainBtn-cf1599ad.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/ContainerForm-4e418d51.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/index-9e27b9a5.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/index-d56192c0.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/BaseInput-71f88b96.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/NavBar-8348630c.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/register-b5beeeeb.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/tabbar-1c409faa.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/ContainerCard-73134c2c.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/InvestCard-138dfc09.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/index-c9784ae4.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/BaseList-5462378a.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/mission-9d1baeb5.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/team-cf812b6b.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/mine-8007a541.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/second-905a3b2a.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/select-3e1d5cbb.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/BaseHtml-c928671d.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/recharge-e382c0d8.css">
    <link rel="stylesheet" href="{{asset('public/d')}}/index-650bd478.css">
    <style>
        .in {
            width: 100%;
            height: 38px;
            background: transparent;
        }

        .in:focus-visible {
            outline: none;
            border: none;
        }

        .\:uno\:.site-name.ml-8px.sdsdsd {
            font-size: 30px;
        }

        .container-card {
            background: rgb(0 0 0 / 19%);
        }

        .recharge-wrap .copy-address[data-v-eb7799e8] {
            display: flex;
            align-items: center;
            width: 100%;
            position: relative;
            padding: 0 16px;
            box-sizing: border-box;
            height: 42px;
            border-radius: 12px;
            overflow: hidden;
            background: rgb(103 103 103 / 29%);
        }

        .a-t-1 .base-main-btn-content {
            border-radius: 16px;
            position: relative;
            display: block;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(113.99deg, #114fee 6.12%, #b654fd 83.22%);
            text-align: center;
        }
    </style>
</head>

<body class="">
    <div id="webcrumbs">
        <div class="w-full mx-auto max-w-[400px] mb-20 bg-white font-sans overflow-hidden">
            <div class="p-6 relative">
                <header class="mb-6">
                    <h1 class="text-2xl font-bold text-[#ff7600]">Pagamento via PIX</h1>
                    <p class="text-gray-600 text-sm mt-1">Escaneie o QR code ou copie o código abaixo</p>
                </header>
                <div class="bg-gradient-to-b from-[#ffcfa5] to-white rounded-2xl p-6 shadow-md mb-6">
                    <div class="flex justify-center mb-4">
                        <div
                            class="bg-white p-4 rounded-xl shadow-sm border-2 border-[#ff7600] transform hover:scale-105 transition-transform duration-300">
                            <div id="qrcode" alt="Pix QR code" class="w-48 h-48"></div>
                        </div>
                    </div>
                    <div class="text-center mb-4">
                        <span
                            class="inline-block bg-[#ff7600]/10 text-[#ff7600] font-medium px-3 py-1 rounded-full text-sm">
                            QR Code válido por 30 minutos
                        </span>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 text-sm">Valor a pagar</p>
                        <h2 class="text-3xl font-bold mt-1 mb-2">{{ price($amount) }}</h2>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mb-6">
                    <p class="text-gray-700 font-medium mb-2">Código PIX Copia e Cola</p>
                    <div class="flex items-center">
                        <div
                            class="bg-white flex-1 p-3 rounded-l-lg border border-gray-300 overflow-hidden text-gray-600 text-sm truncate">
                            {{ $paymentCode }}
                        </div>
                        <button
                            id="copyPixBtn"
                            class="bg-[#ff7600] hover:bg-[#be671a] active:bg-[#be671a] text-white py-3 px-4 rounded-r-lg transition-colors duration-200 flex items-center justify-center group">
                            <svg
                                class="w-5 h-5 group-hover:scale-110 transition-transform duration-200"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Clique no botão para copiar o código PIX</p>
                </div>
                <div class="flex flex-col space-y-3">
                    <a
                        href="{{ route('dashboard') }}"
                        class="w-full text-center bg-[#ff7600] hover:bg-[#ff7600] active:bg-[#be671a] text-white font-medium py-3 px-6 rounded-lg shadow-md transform hover:translate-y-[-2px] transition-all duration-200">
                        Já realizei o pagamento
                    </a>
                    <a
                        href="{{ route('dashboard') }}"
                        class="w-full text-center bg-white hover:bg-gray-50 active:bg-gray-100 text-[#ff7600] font-medium py-3 px-6 rounded-lg border-2 border-[#ff7600] transform hover:translate-y-[-2px] transition-all duration-200">
                        Voltar
                    </a>
                </div>
            </div>
            <div class="bg-[#ff7600]/5 p-6 border-t border-[#ff7600]/20">
                <div class="flex items-center mb-4">
                    <div class="bg-[#ff7600]/10 rounded-full p-2 mr-3">
                        <svg
                            class="w-6 h-6 text-[#ff7600]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-700">Informações importantes</h3>
                </div>
                <ul class="text-sm text-gray-600 space-y-2 pl-4">
                    <li class="flex items-start">
                        <span class="text-[#ff7600] mr-2">•</span> O pagamento pode levar até 30 segundos para ser
                        confirmado
                    </li>
                    <li class="flex items-start">
                        <span class="text-[#ff7600] mr-2">•</span> Você receberá um comprovante por e-mail quando o
                        pagamento for aprovado
                    </li>
                    <li class="flex items-start">
                        <span class="text-[#ff7600] mr-2">•</span> Em caso de dúvidas, entre em contato com nosso
                        suporte
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        const paymentCode = '{{ $paymentCode }}';
        const qrcode = new QRCode(document.getElementById('qrcode'), {
            text: paymentCode,
            width: 200,
            height: 200,
            colorDark: '#000',
            colorLight: '#fff',
            correctLevel: QRCode.CorrectLevel.H
        });

        document.getElementById('copyPixBtn').addEventListener('click', () => {
            navigator.clipboard.writeText(paymentCode).then(() => {
                document.getElementById('copyPixBtn').innerText = 'Copiado!';
                setTimeout(() => {
                    document.getElementById('copyPixBtn').innerText = 'Copiar';
                }, 2000);
            });
        });
        const depositId = '{{ $depositId }}';

        let interval = setInterval(async () => {
            try {
                const response = await fetch(`/deposit/status/${depositId}`);
                const data = await response.json();

                if (data.status === 'approved') {
                    clearInterval(interval); // Para o loop aqui!
                    alert('Pagamento aprovado com sucesso!');
                    window.location.href = "{{ route('dashboard') }}";
                }
            } catch (error) {
                console.error('Erro ao verificar status do pagamento:', error);
            }
        }, 2000);
    </script>
    <script>
        tailwind.config = {
            content: ["./src/**/*.{html,js}"],
            theme: {
                name: "Bluewave",
                fontFamily: {
                    sans: [
                        "Open Sans",
                        "ui-sans-serif",
                        "system-ui",
                        "sans-serif",
                        '"Apple Color Emoji"',
                        '"Segoe UI Emoji"',
                        '"Segoe UI Symbol"',
                        '"Noto Color Emoji"'
                    ]
                },
                extend: {
                    fontFamily: {
                        title: [
                            "Lato",
                            "ui-sans-serif",
                            "system-ui",
                            "sans-serif",
                            '"Apple Color Emoji"',
                            '"Segoe UI Emoji"',
                            '"Segoe UI Symbol"',
                            '"Noto Color Emoji"'
                        ],
                        body: [
                            "Open Sans",
                            "ui-sans-serif",
                            "system-ui",
                            "sans-serif",
                            '"Apple Color Emoji"',
                            '"Segoe UI Emoji"',
                            '"Segoe UI Symbol"',
                            '"Noto Color Emoji"'
                        ]
                    },
                    colors: {
                        neutral: {
                            50: "#f7f7f7",
                            100: "#eeeeee",
                            200: "#e0e0e0",
                            300: "#cacaca",
                            400: "#b1b1b1",
                            500: "#999999",
                            600: "#7f7f7f",
                            700: "#676767",
                            800: "#545454",
                            900: "#464646",
                            950: "#282828"
                        },
                        primary: {
                            50: "#f3f1ff",
                            100: "#e9e5ff",
                            200: "#d5cfff",
                            300: "#b7a9ff",
                            400: "#9478ff",
                            500: "#7341ff",
                            600: "#631bff",
                            700: "#611bf8",
                            800: "#4607d0",
                            900: "#3c08aa",
                            950: "#220174",
                            DEFAULT: "#611bf8"
                        }
                    }
                },
                fontSize: {
                    xs: ["12px", {
                        lineHeight: "19.200000000000003px"
                    }],
                    sm: ["14px", {
                        lineHeight: "21px"
                    }],
                    base: ["16px", {
                        lineHeight: "25.6px"
                    }],
                    lg: ["18px", {
                        lineHeight: "27px"
                    }],
                    xl: ["20px", {
                        lineHeight: "28px"
                    }],
                    "2xl": ["24px", {
                        lineHeight: "31.200000000000003px"
                    }],
                    "3xl": ["30px", {
                        lineHeight: "36px"
                    }],
                    "4xl": ["36px", {
                        lineHeight: "41.4px"
                    }],
                    "5xl": ["48px", {
                        lineHeight: "52.800000000000004px"
                    }],
                    "6xl": ["60px", {
                        lineHeight: "66px"
                    }],
                    "7xl": ["72px", {
                        lineHeight: "75.60000000000001px"
                    }],
                    "8xl": ["96px", {
                        lineHeight: "100.80000000000001px"
                    }],
                    "9xl": ["128px", {
                        lineHeight: "134.4px"
                    }]
                },
                borderRadius: {
                    none: "0px",
                    sm: "6px",
                    DEFAULT: "12px",
                    md: "18px",
                    lg: "24px",
                    xl: "36px",
                    "2xl": "48px",
                    "3xl": "72px",
                    full: "9999px"
                },
                spacing: {
                    0: "0px",
                    1: "4px",
                    2: "8px",
                    3: "12px",
                    4: "16px",
                    5: "20px",
                    6: "24px",
                    7: "28px",
                    8: "32px",
                    9: "36px",
                    10: "40px",
                    11: "44px",
                    12: "48px",
                    14: "56px",
                    16: "64px",
                    20: "80px",
                    24: "96px",
                    28: "112px",
                    32: "128px",
                    36: "144px",
                    40: "160px",
                    44: "176px",
                    48: "192px",
                    52: "208px",
                    56: "224px",
                    60: "240px",
                    64: "256px",
                    72: "288px",
                    80: "320px",
                    96: "384px",
                    px: "1px",
                    0.5: "2px",
                    1.5: "6px",
                    2.5: "10px",
                    3.5: "14px"
                }
            },
            plugins: [],
            important: "#webcrumbs"
        }
    </script>
</body>

</html>