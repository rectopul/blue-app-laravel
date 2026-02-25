<html lang="en" translate="no"
    style="--vh: 7.38px; --primary: #114fee; --bg:linear-gradient(180deg, #fff, #fff); --bg-tab: transparent; --bg-tabbar: linear-gradient(180deg,#fff,#fff); --bg-input: transparent; --btn-bg:linear-gradient(104deg, #16ff4f, #14c35c) !important;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="{{asset('app')}}/assets/index-hgAkstFo.css">
    <title>{{env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{asset('app')}}/assets/default-B-suEvNA.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/ContainerCard-CZ4SLchf.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseMainBtn-8WfiNfVu.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/mine-CszvNRA9.css">
    <style>
        .van-popup--bottom {
            transition: .5s;
            bottom: -100%;
        }

        .a-t-1 .bg-wrap {
            background-size: cover;
            height: unset;
        }
    </style>
</head>

<body class="">
    <div id="app" data-v-app="" class="a-t-1 no-1">
        <div class="box-border w-full">
            <nav class="nav-bar-wrap" style="display: none; --71e539d3: 0;">
                <div class="nav-bar">
                    <div id="navBarItem" class="nav-bar-content h-full w-full">
                        <div class="left">
                            <div class=":uno: base-logo flex items-center"><img class="site-img h-full w-full"
                                    src="{{asset('app')}}/assets/6646d7580447.webp"
                                    draggable="false" alt="logo"
                                    style="border-radius: 50%;"></div>
                            <p class="name font-ali" style="font-size: 20px;">{{env('APP_NAME')}}</p>
                        </div>
                        <div class="right">
                            <div class="bg-$bg-weight px-10px py-4px mr-8px text-12px rounded-full flex items-center">
                                <div class="i-ph:download-fill w-18px h-18px cursor-pointer mr-2px"></div>
                                App
                            </div>
                            <div><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMpSURBVHgBxVnhsdowDDa9/i8b1COwwcsGfRuUEegEyQawQd4IrxOQThA6gekEoRN8tYk5FEVyTIC+706XI1akz7KtSGFhZgJA4S8rLy/xuowScPJyjPLbS7NYLBrzbHhSSy+llw63w3mpvVjzaERiWzwO9cOIekMb5Eds56Vi97RnnZe1uQeQo9ZFEit2vybP7Zj+Kt5zgr2tuRXol3SvEFsK5B3IksXnaeQqcr8SSLYXu7kEW2bAge0ZDKNRCzYokY6NWYyjuTc5wHhZGz47/7tgOlawY5lOwcalVdpOkVtzcooe3WNtwh4lsMvQCdhoxnjYHZR9geEWqBIEq6mJxEhSv53o1998YzOxCYMURYIg3wrahC2Gh2orKSAzKgOnJoEbJ1Opk2HRcxBmGp0VGEfaQU7GHcYntY42NPuj1HQZpIa2LFrhQLR4PNpou1Ci2FESFCHrl5hXEMyFQ5/e+NupWKAPZRkncYrX3Kz+5uWX6csqCcHOq5fvZh5+hAi+T8yuQR/6897xciRjr1MeMMytLt4ros3DhO936bUWcHnvWsEhXfoigyDdQp0wHjJIOHhO4OG4w4CwcZcJh7iDIBJ6F6KDQHGHVYbDpxAk+vQ1ik9s/Iv5eAy3gbDENRLl+LMiCLmtOC/xEWM4jSiec0hKyHm3/ex1Dl6+suesl3UQrxTGf5pr6/jXXPOkNdOgB+6E/gBe2tUiioY/gWDj5VtCaRWlFFboxd8Lyf2kPGsZgfC7Mzr4i+Kgveq0BudZ0Bqx4kwTShWBa7EwlfHn4IBxsTBoxAwZGFQRuK3cOkIvt/gBrJEutxzV5YMUlVGAzCqZ6FMUCb2S6VquwJttqxh6Vsk/iLTmmC6Vw8c0TU4LTlDesJnsFb3dlI4wkdy2c21SYM4RDfyvxn1nciA86DD+9CE3ONdxetqdQN4xH63JRZydVMiWuH48UlOTQGBL7JYYp6UGt3w8Io52AkmHa4MjNtsY58pUI5a3rAmSa+ivPe6wRP6X2PDsxjwCkMvxe5BsKx5B1OF2DD6A5mJhZgJ92ggSSjFr+pqS/g0R6sZQSzbhOvdviH8uYjIePpJc9gAAAABJRU5ErkJggg=="
                                    class="w-20px h-20px"></div>
                        </div>
                    </div>
                </div>
            </nav>
            <div data-v-b5431ff1="" class="navigation" style="display: none;">
                <div data-v-b5431ff1="" class="navigation-content">Me
                    <div data-v-b5431ff1="" class="tools">
                        <div data-v-b5431ff1=""
                            class="icon cursor-pointer i-material-symbols-arrow-back-ios-new-rounded"></div>
                        <div data-v-b5431ff1="" id="navigation-right" class="cursor-pointer"></div>
                    </div>
                </div>
            </div>

            <?php

            use App\Models\User;

            $user = \Auth::user();

            //First Level Users
            $first_level_users = User::where('ref_by', $user->ref_id)->get();
            $first_level_users_ids = [];
            foreach ($first_level_users as $user) {
                array_push($first_level_users_ids, $user->id);
            }

            //Second Level Users
            $second_level_users_ids = [];
            foreach ($first_level_users as $element) {
                $users = User::where('ref_by', $element->ref_id)->get();
                foreach ($users as $user) {
                    array_push($second_level_users_ids, $user->id);
                }
            }
            $second_level_users = User::whereIn('id', $second_level_users_ids)->get();

            //Third Level Users
            $third_level_users_ids = [];
            foreach ($second_level_users as $element) {
                $users = User::where('ref_by', $element->ref_id)->get();
                foreach ($users as $user) {
                    array_push($third_level_users_ids, $user->id);
                }
            }
            $third_level_users = User::whereIn('id', $third_level_users_ids)->get();
            $team_size = $first_level_users->count() + $second_level_users->count() + $third_level_users->count();
            ?>

            <div data-v-e14c1e8d="" class="bg-wrap mine-wrap">
                <div data-v-e14c1e8d="" class="relative z-1 px-$mg">
                    <div style="display: flex; align-items: center; padding: 20px; background: #f9f9f9; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 20px;">
                        <div style="width: 60px; height: 60px; margin-right: 15px; flex-shrink: 0;">
                            <img src="{{asset('app')}}/6646d7580447.webp" alt="logo" draggable="false"
                                style="width: 100%; height: 100%; border-radius: 50%;">
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-weight: bold; font-size: 16px; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{user()->email ?? user()->phone}}
                            </div>
                            <div style="font-size: 14px; color: #666; margin-top: 4px;">
                                Código de Convite: <span style="font-weight: bold;">{{user()->ref_id}}</span>
                                <button onclick="copyLink('{{user()->ref_id}}')" style="margin-left: 10px; padding: 4px 10px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer;">Copy</button>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; gap: 15px; margin-bottom: 20px;">
                        <div style="flex: 1; background: #ffffff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                            <div style="font-size: 14px; color: #888;">Valor Total Sacado</div>
                            <div style="font-weight: bold; font-size: 18px; margin-top: 5px; color: #2c3e50;">{{price(\App\Models\Withdrawal::where('user_id', user()->id)->where('status', 'approved')->sum('amount'))}}</div>
                        </div>
                        <div style="flex: 1; background: #ffffff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                            <div style="font-size: 14px; color: #888;">Valor Total Depositado</div>
                            <div style="font-weight: bold; font-size: 18px; margin-top: 5px; color: #2c3e50;">{{price(\App\Models\Deposit::where('user_id', user()->id)->where('status', 'approved')->sum('amount'))}}</div>
                        </div>
                        <div style="flex: 1; background: #ffffff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                            <div style="font-size: 14px; color: #888;">Saldo</div>
                            <div style="font-weight: bold; font-size: 18px; margin-top: 5px; color: #2c3e50;">{{price(user()->balance)}}</div>
                        </div>
                    </div>

                    <ul style="list-style: none; padding: 0; margin: 0; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); padding: 20px;">
                        <li style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <div>
                                <p style="font-weight: bold; font-size: 18px; color: #27ae60;">{{price(\App\Models\UserLedger::where('user_id', user()->id)->where('reason', 'daily_income')->sum('amount'))}}</p>
                                <p style="color: #888;">Total Retornos ({{currency()}})</p>
                            </div>
                            <div>
                                <p style="font-weight: bold; font-size: 18px; color: #2980b9;">{{price(\App\Models\UserLedger::where('user_id', user()->id)->where('reason', 'commission')->sum('amount'))}}</p>
                                <p style="color: #888;">Total Comissão</p>
                            </div>
                        </li>
                        <li style="display: flex; justify-content: space-between;">
                            <div>
                                <p style="font-weight: bold; font-size: 18px; color: #27ae60;">{{price(\App\Models\UserLedger::where('user_id', user()->id)->where('reason', 'daily_income')->where('created_at', 'like', date("Y-m-d")."%")->sum('amount'))}}</p>
                                <p style="color: #888;">Ganhos de Hoje ({{currency()}})</p>
                            </div>
                            <div>
                                <p style="font-weight: bold; font-size: 18px; color: #2980b9;">{{price(\App\Models\UserLedger::where('user_id', user()->id)->where('reason', 'commission')->where('created_at', 'like', date("Y-m-d")."%")->sum('amount'))}}</p>
                                <p style="color: #888;">Comissão Hoje</p>
                            </div>
                        </li>
                        <li style="display: flex; justify-content: space-between;">
                            <div>
                                <p style="font-weight: bold; font-size: 18px; color: #8e44ad;">{{$team_size}}</p>
                                <p style="color: #888;">Tamanho do Time</p>
                            </div>
                        </li>
                    </ul>
                    <div data-v-e14c1e8d="" class="gold_img"
                        style="background-image: url({{asset('app')}}/assets/coinbg.webp);">
                        <div data-v-e14c1e8d="" class="n">{{env('APP_NAME')}} system</div>
                        <div data-v-e14c1e8d="" class="s">Um símbolo de investimento seguro.</div>
                    </div>

                    <div class="van-overlay ob" role="button" tabindex="0" style="z-index: 2007;display: none" onclick="closeService()"></div>
                    <div role="dialog" tabindex="0" class="van-popup services van-popup--round van-popup--bottom overflow-hidden obContainer"
                        style="z-index: 2007; height: 50%;">
                        <div class=":uno: m-10px h-full flex flex-col overflow-hidden a-t-1">
                            <div class="text-center text-lg font-bold text-#1d2129">Atendimento 24 Horas</div>
                            <div class="my-10px text-center text-sm text-#86909c">Entre em contato com o suporte pela plataforma de sua preferência
                            </div>
                            <ul class=":uno: mx-auto my-10px max-w-$maxWidth w-full flex-1 overflow-y-auto">
                                <li class=":uno: mb-8px flex items-center rounded-12px bg-#F7F8FA p-8px">
                                    <a href="https://wa.link/2mgt9t" target="_blank" class="flex items-center w-full">
                                        <img
                                            class=":uno: h-64px w-64px overflow-hidden rounded-12px"
                                            style="width:50px;height:50px;"
                                            src="https://img.icons8.com/fluency/48/whatsapp.png">
                                        <span class=":uno: ml-10px text-#1d2129">WhatsApp</span>
                                        <div class="i-ic-round-keyboard-arrow-right ml-auto text-20px text-#86909c"></div>
                                    </a>
                                </li>
                                <li class=":uno: mb-8px flex items-center rounded-12px bg-#F7F8FA p-8px">
                                    <a href="https://wa.link/2mgt9t" target="_blank" class="flex items-center w-full">
                                        <img
                                            class=":uno: h-64px w-64px overflow-hidden rounded-12px"
                                            style="width:50px;height:50px;"
                                            src="https://img.icons8.com/fluency/48/whatsapp.png">
                                        <span class=":uno: ml-10px text-#1d2129">Entrar em Contato</span>
                                        <div class="i-ic-round-keyboard-arrow-right ml-auto text-20px text-#86909c"></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div data-v-e14c1e8d=""
                        class=":uno: container-card relative rd-$card-radius p-$mg c-$btn-text px-12px py-0">
                        <div data-v-e14c1e8d="" class="menu-item" onclick="window.location.href='{{route('history')}}'">
                            <div data-v-e14c1e8d="" class="flex items-center"><img data-v-e14c1e8d=""
                                    src="{{asset('/')}}/r.png"
                                    class="w-26.48px h-26.48px shrink-0"><span
                                    data-v-e14c1e8d="" class="name">Histórico</span></div>
                            <i data-v-e14c1e8d="" class="van-badge__wrapper van-icon van-icon-arrow"
                                style="color: rgb(171, 171, 179); font-size: 15px;"></i>
                        </div>
                        <!--<div data-v-e14c1e8d="" class="menu-item" onclick="window.location.href='{{route('user.change.tpassword')}}'">
                        <div data-v-e14c1e8d="" class="flex items-center"><img data-v-e14c1e8d=""
                                                                               src="{{asset('/')}}/s.png"
                                                                               class="w-26.48px h-26.48px shrink-0"><span
                                data-v-e14c1e8d="" class="name">Security Password</span></div>
                        <i data-v-e14c1e8d="" class="van-badge__wrapper van-icon van-icon-arrow"
                           style="color: rgb(171, 171, 179); font-size: 15px;"></i></div>
                    <div data-v-e14c1e8d="" class="menu-item" onclick="window.location.href=' {{route('user.change.password')}}'">
                        <div data-v-e14c1e8d="" class="flex items-center"><img data-v-e14c1e8d=""
                                                                               src="{{asset('/')}}/l.png"
                                                                               class="w-26.48px h-26.48px shrink-0"><span
                                data-v-e14c1e8d="" class="name"> Login Password</span></div>
                        <i data-v-e14c1e8d="" class="van-badge__wrapper van-icon van-icon-arrow"
                           style="color: rgb(171, 171, 179); font-size: 15px;"></i></div>-->
                    </div>
                    <div data-v-e14c1e8d=""
                        class=":uno: container-card relative rd-$card-radius p-$mg c-$btn-text px-12px py-0 mt-12px">
                        <div data-v-e14c1e8d="" class="menu-item" onclick="openService()">
                            <div data-v-e14c1e8d="" class="flex items-center"><img data-v-e14c1e8d=""
                                    src="{{asset('/')}}/h.png"
                                    class="w-26.48px h-26.48px shrink-0"><span
                                    data-v-e14c1e8d="" class="name">Suporte</span></div>
                            <i data-v-e14c1e8d="" class="van-badge__wrapper van-icon van-icon-arrow"
                                style="color: rgb(171, 171, 179); font-size: 15px;"></i>
                        </div>

                        <!--<div data-v-e14c1e8d="" class="menu-item" onclick="window.location.href='{{route('user.bank.create')}}'">
                        <div data-v-e14c1e8d="" class="flex items-center"><img data-v-e14c1e8d=""
                                                                               src="{{asset('/')}}/555.png"
                                                                               class="w-26.48px h-26.48px shrink-0"><span
                                data-v-e14c1e8d="" class="name">Add Account</span></div>
                        <i data-v-e14c1e8d="" class="van-badge__wrapper van-icon van-icon-arrow"
                           style="color: rgb(171, 171, 179); font-size: 15px;"></i></div>
                    <div data-v-e14c1e8d="" class="menu-item" onclick="window.location.href='{{route('user.download.apk')}}'">
                        <div data-v-e14c1e8d="" class="flex items-center">
                            <div data-v-e14c1e8d="" class="i-streamline:download-computer w-20px h-20px shrink-0"></div>
                            <span data-v-e14c1e8d="" class="name">APP Download</span></div>
                        <i data-v-e14c1e8d="" class="van-badge__wrapper van-icon van-icon-arrow"
                           style="color: rgb(171, 171, 179); font-size: 15px;"></i></div>-->
                    </div>
                    <a data-v-e14c1e8d="" href="javascript:void(0)" onclick="logoutt()" class="base-main-btn flex items-center justify-center mt-22px!">
                        <div class="base-main-btn-content">LogOut</div>
                    </a>
                </div>
                <div style="height: 100px;"></div>
            </div>
            @include('app.layout.manu')
        </div>
        <div></div>
    </div>


    <div data-v-app=""></div>
    <div role="dialog" tabindex="0" class="van-popup van-popup--center van-toast van-toast--middle van-toast--loading"
        style="z-index: 2002; display: block;">
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

    <script>
        window.onload = function() {
            document.querySelector('.van-toast--loading').style.display = 'none';
        };
    </script>
    <script>
        function closeService() {
            document.querySelector('.ob').style.display = 'none';
            document.querySelector('.services').style.bottom = '-100%';
        }

        function openService() {
            document.querySelector('.ob').style.display = 'block';
            document.querySelector('.services').style.bottom = '0%';
        }
    </script>
    @include('alert-message')
    <script>
        function copyLink(text) {
            const body = document.body;
            const input = document.createElement("input");
            body.append(input);
            input.style.opacity = 0;
            input.value = text.replaceAll(' ', '');
            input.select();
            input.setSelectionRange(0, input.value.length);
            document.execCommand("Copy");
            input.blur();
            input.remove();
            message('Copied success..')
        }

        function logoutt() {
            document.querySelector('.van-toast--loading').style.display = 'block';
            window.location.href = '{{url('
            logout ')}}'
        }
    </script>
</body>

</html>