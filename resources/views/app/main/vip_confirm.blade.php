<html lang="en" translate="no"
    style="--vh: 7.38px; --primary: #114fee; --bg: #fff; --bg-tab: transparent; --bg-tabbar: linear-gradient(180deg,#fff,#fff); --bg-input: transparent; --btn-bg: linear-gradient(104deg, #16ff4f, #14c35c) !important;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="{{asset('app')}}/assets/index-hgAkstFo.css">
    <title>{{env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{asset('app')}}/assets/default-B-suEvNA.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseMainBtn-8WfiNfVu.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseInput-QBaNTw53.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/ContainerCard-CZ4SLchf.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/confirm-oeSAxNvb.css">

    <style>
        #app {
            min-height: 60%;
        }

        .a-t-1 .bg-wrap {
            height: 85%;
        }
    </style>
</head>

<body>
    <div id="app" data-v-app="" class="a-t-1 no-1">
        <div class="box-border w-full">
            <div data-v-b5431ff1="" class="navigation">
                <div data-v-b5431ff1="" class="navigation-content">Detalhes do Investimento
                    <div data-v-b5431ff1="" class="tools">
                        <div data-v-b5431ff1=""
                            onclick="window.location.href='{{route('dashboard')}}'"
                            class="icon cursor-pointer i-material-symbols-arrow-back-ios-new-rounded"></div>
                        <div data-v-b5431ff1="" id="navigation-right" class="cursor-pointer"></div>
                    </div>
                </div>
            </div>
            <div data-v-2540c079="" class="bg-wrap second-wrap invest-confirm-wrap px-$mg">
                <div data-v-2540c079="" class="title">Valor</div>
                <div data-v-2540c079="" class="base-input is-text">
                    <div class="input-box">
                        <div class="input-left-slot"></div>
                        <input type="text" placeholder="Por favor insira o valor do investimento" disabled="" class="w-full" value="{{$package->price}}">

                        <div class="input-right-slot">{{currency()}}</div>
                    </div>
                </div>
                <div data-v-2540c079="" class="mt-5px text-12px text-black">Saldo disponível: {{price(user()->balance)}}</div>
                <div data-v-2540c079="" class="title mt-24px">O que é preciso saber?</div>

                <div data-v-2540c079="">
                    <div data-v-2540c079="" class="info">
                        <p data-v-2540c079="">Projeto: </p>
                        <div data-v-2540c079="">{{$package->name}}</div>
                    </div>
                    <div data-v-2540c079="" class="info">
                        <p data-v-2540c079="">Pagamento Diário:</p>
                        <div data-v-2540c079="">{{price($package->commission_with_avg_amount / $package->validity)}}</div>
                    </div>
                    <div data-v-2540c079="" class="info">
                        <p data-v-2540c079="">Ciclo：</p>
                        <div data-v-2540c079="">{{$package->validity}} Dias</div>
                    </div>
                    <div data-v-2540c079="" class="info">
                        <p data-v-2540c079="">Retorno Final：</p>
                        <div data-v-2540c079="">{{price($package->commission_with_avg_amount)}}</div>
                    </div>
                </div>

                <?php
                $myPackage = \App\Models\Purchase::where('user_id', user()->id)->where('package_id', $package->id)->where('status', 'active')->first();
                ?>

                @if(!$myPackage)
                <a data-v-2540c079=""
                    href="javascript:void(0)"
                    onclick="buyConfirm('{{$package->id}}')"
                    style="background: #114fee;"
                    class="base-main-btn flex items-center justify-center absolute! bottom-$mg! w-90%! left-50%! translate-x-[-50%]!">
                    <div class="base-main-btn-content">Confirmar</div>
                </a>
                @endif
            </div>
            @include('app.layout.manu')
        </div>
        <div></div>
    </div>


    <div data-v-app=""></div>
    <div role="dialog" tabindex="0" class="van-popup van-popup--center van-toast van-toast--middle van-toast--loading"
        style="z-index: 2002;">
        <div class="van-loading van-loading--circular van-toast__loading" aria-live="polite" aria-busy="true"><span
                class="van-loading__spinner van-loading__spinner--circular"><svg class="van-loading__circular"
                    viewBox="25 25 50 50">
                    <circle cx="50"
                        cy="50"
                        r="20"
                        fill="none"></circle>
                </svg></span>
        </div>
    </div>
    @include('alert-message')
    <script>
        window.onload = function() {
            document.querySelector('.van-toast--loading').style.display = 'none';
        };

        function buyConfirm(id) {
            document.querySelector('.van-toast--loading').style.display = 'block';
            window.location.href = '{{url('
            purchase / confirmation ')}}' + "/" + id;
        }
    </script>
</body>

</html>