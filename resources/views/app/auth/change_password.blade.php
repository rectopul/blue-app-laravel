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
</head>

<body class="">
    <div id="app" data-v-app="" class="a-t-1 no-1">
        <div class="box-border w-full">
            <div data-v-b5431ff1="" class="navigation">
                <div data-v-b5431ff1="" class="navigation-content">Login Password
                    <div data-v-b5431ff1="" class="tools">
                        <div data-v-b5431ff1=""
                            onclick="window.location.href='{{route('profile')}}'"
                            class="icon cursor-pointer i-material-symbols-arrow-back-ios-new-rounded"></div>
                        <div data-v-b5431ff1="" id="navigation-right" class="cursor-pointer"></div>
                    </div>
                </div>
            </div>
            <div class="second-wrap bg-wrap px-$mg">
                <div class=":uno: container-card relative rd-$card-radius p-$mg c-$btn-text mt-20px">
                    <form action="{{route('user.change.password.confirmation')}}" method="post">
                        @csrf
                        <div class="base-input is-password">
                            <div class="input-box">
                                <div class="input-left-slot"></div>
                                <input autocomplete="new-password" placeholder="Old login password" class="w-full"
                                    name="old_password"
                                    type="password">
                                <div class="input-pwd-eye-slot cursor-pointer">
                                    <div class="input-pwd-eye" onclick="eye()">
                                        <div class="i-mdi-eye-outline"></div>
                                    </div>
                                </div>
                                <div class="input-right-slot"></div>
                            </div>
                        </div>
                        <div class="base-input is-password">
                            <div class="input-box">
                                <div class="input-left-slot"></div>
                                <input autocomplete="new-password" placeholder="New login password" class="w-full"
                                    name="new_password"
                                    type="password">
                                <div class="input-pwd-eye-slot cursor-pointer">
                                    <div class="input-pwd-eye" onclick="eye1()">
                                        <div class="i-mdi-eye-outline"></div>
                                    </div>
                                </div>
                                <div class="input-right-slot"></div>
                            </div>
                        </div>
                        <div class="base-input is-password">
                            <div class="input-box">
                                <div class="input-left-slot"></div>
                                <input autocomplete="new-password" placeholder="Confirm new password" class="w-full"
                                    name="confirm_password"
                                    type="password">
                                <div class="input-pwd-eye-slot cursor-pointer">
                                    <div class="input-pwd-eye" onclick="eye2()">
                                        <div class="i-mdi-eye-outline"></div>
                                    </div>
                                </div>
                                <div class="input-right-slot"></div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" onclick="changePassword()" class="base-main-btn flex items-center justify-center mt-20px!">
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

        function changePassword() {
            document.querySelector('.van-toast--loading').style.display = 'block';
            document.querySelector('form').submit();
        }

        function eye() {
            var pass = document.querySelector('input[name="old_password"]');
            if (pass.type == 'password') {
                pass.type = 'text'
            } else {
                pass.type = 'password'
            }
        }

        function eye1() {
            var pass = document.querySelector('input[name="new_password"]');
            if (pass.type == 'password') {
                pass.type = 'text'
            } else {
                pass.type = 'password'
            }
        }

        function eye2() {
            var pass = document.querySelector('input[name="confirm_password"]');
            if (pass.type == 'password') {
                pass.type = 'text'
            } else {
                pass.type = 'password'
            }
        }
    </script>
</body>

</html>