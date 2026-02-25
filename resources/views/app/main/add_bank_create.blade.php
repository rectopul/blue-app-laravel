<html lang="en" translate="no"
    style="--vh: 7.38px; --primary: #114fee; --bg: #fff; --bg-tab: transparent; --bg-tabbar: linear-gradient(180deg,#120938,#0b0822); --bg-input: transparent; --btn-bg: linear-gradient(104deg, #16ff4f, #14c35c) !important;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" href="{{asset('app')}}/assets/index-hgAkstFo.css">
    <title>{{env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{asset('app')}}/assets/default-B-suEvNA.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/ContainerCard-CZ4SLchf.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseMainBtn-8WfiNfVu.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseInput-QBaNTw53.css">
    <style>
        select#bschsvcs {
            background: rgba(255, 255, 255, .12);
            width: 100%;
            padding: 11px -1px;
        }

        select#bschsvcs:focus-visible {
            outline: none;
        }

        .a-t-1 .bg-wrap {
            background-size: cover;
            height: 100%;
        }
    </style>
</head>

<body class="">
    <div id="app" data-v-app="" class="a-t-1 no-1">
        <div class="box-border w-full">
            <div data-v-b5431ff1="" class="navigation">
                <div data-v-b5431ff1="" class="navigation-content">Modify bank account
                    <div data-v-b5431ff1="" class="tools">
                        <div data-v-b5431ff1=""
                            onclick="window.location.href='{{route('dashboard')}}'"
                            class="icon cursor-pointer i-material-symbols-arrow-back-ios-new-rounded"></div>
                        <div data-v-b5431ff1="" id="navigation-right" class="cursor-pointer"></div>
                    </div>
                </div>
            </div>
            <div class="second-wrap bg-wrap px-$mg">
                <div class=":uno: container-card relative rd-$card-radius p-$mg c-$btn-text mt-20px">
                    <form action="{{route('setup.gateway.submit')}}" method="post">
                        @csrf
                        <div class="base-input is-password">
                            <div class="input-box">
                                <div class="input-left-slot"></div>
                                <input placeholder="Enter your real name" class="w-full"
                                    name="name"
                                    type="text">
                                <div class="input-right-slot"></div>
                            </div>
                        </div>
                        <div class="base-input is-password">
                            <div class="input-box">
                                <div class="input-left-slot"></div>
                                <select name="gateway_method" id="bschsvcs">
                                    <option value="">Select Channel</option>
                                    @foreach(\App\Models\PaymentMethod::get() as $element)
                                    <option value="{{$element->name}}">{{$element->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-right-slot"></div>
                            </div>
                        </div>
                        <div class="base-input is-password">
                            <div class="input-box">
                                <div class="input-left-slot"></div>
                                <input placeholder="Enter your wallet address" class="w-full"
                                    name="gateway_number"
                                    type="text">
                                <div class="input-right-slot"></div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" onclick="bank()" class="base-main-btn flex items-center justify-center mt-20px!">
                            <div class="base-main-btn-content">Confirm</div>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div></div>
    </div>



    <div data-v-app=""></div>
    <div role="dialog" tabindex="0" class="van-popup van-popup--center van-toast van-toast--middle van-toast--loading"
        style="z-index: 2001; display: block;">
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

        function bank() {
            document.querySelector('.van-toast--loading').style.display = 'block';
            document.querySelector('form').submit();
        }
    </script>
</body>

</html>