<html lang="en" translate="no"
    style="--vh: 9.25px; --primary: #114fee; --bg: #000; --bg-tab: transparent; --bg-tabbar: linear-gradient(180deg,#120938,#0b0822); --bg-input: transparent; --btn-bg: linear-gradient(113.99deg,#114fee 6.12%,#b654fd 83.22%);">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" crossorigin="" href="{{asset('app')}}/assets/index-BuG2juEB.css">
    <title>{{env('APP_NAME')}}</title>
    <style id="ss-chat-custom-css">
        .ss-chat-body {
            overflow: hidden !important
        }
    </style>
    <link rel="stylesheet" href="{{asset('app')}}/assets/default-B-suEvNA.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/ContainerCard-CZ4SLchf.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/VideoCard-DqKopoFF.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/index-aU0vskSt.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseEmpty-DK-_bQDv.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseUserTab-BT60kelY.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/index-Dt9y-AzG.css">
    <link rel="stylesheet" href="{{asset('app')}}/assets/BaseHtml-CEbVem4v.css">

    <style>
        .a-t-1 .bg-wrap {
            background: url({{asset('app')
        }
        }

        /assets/bg_1-CXVUQEx-.png) no-repeat top;
        background-size: cover;
        height: calc(var(--vh, 1vh) * 100);
        }
    </style>
</head>

<body class="">
    <div id="app" data-v-app="" class="a-t-1 no-1">
        <div class="box-border w-full">
            <div data-v-b5431ff1="" class="navigation" style="">
                <div data-v-b5431ff1="" class="navigation-content">Help Center
                    <div data-v-b5431ff1="" class="tools">
                        <div data-v-b5431ff1=""
                            onclick="window.location.href='{{route('dashboard')}}'"
                            class="icon cursor-pointer i-material-symbols-arrow-back-ios-new-rounded"></div>
                        <div data-v-b5431ff1="" id="navigation-right" class="cursor-pointer"></div>
                    </div>
                </div>
            </div>
            <div data-v-7ba097bc="" class="bg-wrap second-wrap help-wrap px-$mg">
                <div data-v-7ba097bc="" class="base-user-tab" style="--7083036f: 5;">
                    <div class="tab-item h-full flex cursor-pointer items-center justify-center">
                        <div class="text-center">Online Service</div>
                    </div>

                </div>
                <div data-v-7ba097bc="" class=":uno: container-card relative rd-$card-radius p-$mg c-$btn-text px-$mg py-0">
                    <a data-v-7ba097bc="" href=""
                        class="flex items-center justify-between py-12px border-b border-$border-color border-solid last:b-none">
                        <div data-v-7ba097bc="" class="text-12px">What'sapp Channel</div>
                        <div data-v-7ba097bc="" class="i-material-symbols-light:arrow-forward-ios text-$text-gray"></div>
                    </a><a data-v-7ba097bc="" href=""
                        class="flex items-center justify-between py-12px border-b border-$border-color border-solid last:b-none">
                        <div data-v-7ba097bc="" class="text-12px">Customer Service</div>
                        <div data-v-7ba097bc="" class="i-material-symbols-light:arrow-forward-ios text-$text-gray"></div>
                    </a><a data-v-7ba097bc="" href=""
                        class="flex items-center justify-between py-12px border-b border-$border-color border-solid last:b-none">
                        <div data-v-7ba097bc="" class="text-12px">Telegram</div>
                        <div data-v-7ba097bc="" class="i-material-symbols-light:arrow-forward-ios text-$text-gray"></div>
                    </a>
                </div>
            </div>
            @include('app.layout.manu')
        </div>
    </div>
</body>

</html>