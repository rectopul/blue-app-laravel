<html lang="en" translate="no"
    style="--vh: 9.25px; --primary: #114fee; --bg: #000; --bg-tab: transparent; --bg-tabbar: linear-gradient(180deg,#fff,#fff); --bg-input: transparent; --btn-bg: linear-gradient(113.99deg,#114fee 6.12%,#b654fd 83.22%);">

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
            background: #fff;
            background-size: cover;
            height: calc(var(--vh, 1vh) * 100);
        }
    </style>
</head>

<body class="">
    <div id="app" data-v-app="" class="a-t-1 no-1">
        <div class="box-border w-full">
            <div data-v-b5431ff1="" class="navigation" style="">
                <div data-v-b5431ff1="" class="navigation-content">
                    @if($generation == 1)
                    First Level Generation
                    @elseif($generation == 2)
                    Second Level Generation
                    @elseif($generation == 3)
                    Third Level Generation
                    @else
                    Illegal Generation Code
                    @endif
                    <div data-v-b5431ff1="" class="tools">
                        <div data-v-b5431ff1=""
                            onclick="window.location.href='{{route('user.team')}}'"
                            class="icon cursor-pointer i-material-symbols-arrow-back-ios-new-rounded"></div>
                        <div data-v-b5431ff1="" id="navigation-right" class="cursor-pointer"></div>
                    </div>
                </div>
            </div>
            <div data-v-7ba097bc="" class="bg-wrap second-wrap help-wrap px-$mg">
                <div data-v-7ba097bc="" class=":uno: container-card relative rd-$card-radius p-$mg c-$btn-text px-$mg py-0">
                    @foreach($users as $element)
                    <a data-v-7ba097bc="" href="javascript:void(0)" class="flex items-center justify-between py-12px border-b border-$border-color border-solid last:b-none">
                        <div data-v-7ba097bc="" class="text-12px">
                            UID: {{$element->ref_id}} <br>
                            Invest: {{price(\App\Models\Deposit::where('status', 'approved')->where('user_id', $element->id)->sum('amount'))}} <br>
                            CashOut: {{price(\App\Models\Withdrawal::where('status', 'approved')->where('user_id', $element->id)->sum('amount'))}} <br>
                            Join Me: {{$element->created_at}} <br>
                        </div>
                        <div data-v-7ba097bc="" class="i-material-symbols-light:arrow-forward-ios text-$text-gray"></div>
                    </a>
                    @endforeach
                </div>
            </div>
            @include('app.layout.manu')
        </div>
    </div>
</body>

</html>