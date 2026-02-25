<html style="--status-bar-height: 0px; --top-window-height: 0px; --window-left: 0px; --window-right: 0px; --window-margin: 0px; --window-top: calc(var(--top-window-height) + calc(44px + env(safe-area-inset-top))); --window-bottom: 0px;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recharge record</title>
    <meta property="og:title" content="Agridevelop Plus">
    <meta property="og:image" content="{{asset('public')}}/static/login/logo.png">
    <meta name="description"
          content="In the previous Agridevelop project, many people made their first pot of gold through the Agridevelop App. The new AgriDevelop Plus App has just opened registration in March 2024. We will build the best and most long-lasting online money-making application in India. Join AgriDevelop as soon as possible and you will have the best opportunity to make money.	">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, viewport-fit=cover">
    <link rel="stylesheet" href="{{asset('public')}}/static/index.2da1efab.css">
    <link rel="stylesheet" href="{{asset('public')}}/record.css">
</head>
<body class="uni-body pages-my-rechargelist">
<uni-app class="uni-app--maxwidth">
    <uni-page data-page="pages/my/rechargelist">
        <uni-page-head uni-page-head-type="default">
            <div class="uni-page-head" style="background-color: rgb(13, 165, 97); color: rgb(255, 255, 255);">
                <div class="uni-page-head-hd">
                    <div class="uni-page-head-btn" onclick="window.location.href='{{route('profile')}}'"><i class="uni-btn-icon"
                                                                                                            style="color: rgb(255, 255, 255); font-size: 27px;"></i></div>
                    <div class="uni-page-head-ft"></div>
                </div>
                <div class="uni-page-head-bd">
                    <div class="uni-page-head__title" style="font-size: 16px; opacity: 1;"> Recharge record</div>
                </div>
                <div class="uni-page-head-ft"></div>
            </div>
            <div class="uni-placeholder"></div>
        </uni-page-head>
        <uni-page-wrapper>
            <uni-page-body>
                <uni-view data-v-52b6708e="" class="content">
                    <uni-view data-v-7cf13343="" data-v-52b6708e="">

                        @if(\App\Models\Deposit::where('user_id', auth()->id())->count() > 0)
                            <uni-view data-v-52b6708e="" class="msgbox">
                                @foreach(\App\Models\Deposit::where('user_id', auth()->id())->orderByDesc('id')->get() as $element)
                                    <uni-view data-v-52b6708e="" class="item">
                                        <uni-view data-v-52b6708e="" class="item_t">
                                            <uni-view data-v-52b6708e="" class="title">
                                                <uni-view data-v-52b6708e="" class="title_1">
                                                    <svg data-v-52b6708e="" t="1692022407926" viewBox="0 0 1024 1024"
                                                         version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="7191" width="28"
                                                         height="28" class="icon">
                                                        <path data-v-52b6708e=""
                                                              d="M598.208 64H416.192c-22.272 0-40.128 21.952-40.128 49.152s17.92 49.28 40.128 49.28h183.168c21.12 0 39.04-22.08 39.04-49.28 0-27.2-17.856-49.152-40.192-49.152z m197.504 31.104h-90.24c0.832 5.248 1.408 10.56 1.408 15.936 0 40.384-27.008 80.128-59.136 80.128H370.304c-33.792 0-60.864-39.68-60.864-80.128a96 96 0 0 1 1.472-16H219.52C146.048 95.104 128 140.288 128 205.44v645.44C128 920.832 153.856 960 225.088 960h565.12C861.312 960 896 929.28 896 850.88V205.376c0-65.152-32.384-110.272-100.288-110.272z m-91.264 636.992H309.76c-14.528 0-29.056-18.688-29.056-33.92 0-15.168 12.288-28.16 26.88-28.16h393.6c14.528 0 26.88 11.776 27.904 28.16 0 15.296-10.048 33.92-24.576 33.92z m4.288-170.88H313.984c-14.528 0-23.808-16.384-23.808-31.68 0-15.232 12.288-30.464 26.88-30.464h393.6c14.528 0 26.88 14.08 27.904 30.464 0 15.296-15.296 31.68-29.824 31.68z m0-176.128H313.984c-14.528 0-23.808-16.384-23.808-31.616s12.288-30.528 26.88-30.528h393.6c14.528 0 26.88 14.144 27.904 30.528 0 15.232-15.296 31.616-29.824 31.616z"
                                                              fill="#88A4CD" p-id="7192"></path>
                                                    </svg>
                                                </uni-view>
                                                <uni-view data-v-52b6708e="" class="title_2">{{$element->oid}}</uni-view>
                                            </uni-view>
                                            <uni-view data-v-52b6708e="" class="types">Recharge</uni-view>
                                            <uni-view data-v-52b6708e="" class="con">
                                                <uni-view data-v-52b6708e="" class="conitem">
                                                    <uni-view data-v-52b6708e="" class="lab">Amount</uni-view>
                                                    <uni-view data-v-52b6708e="" class="msg">
                                                        <uni-text data-v-0dd1b27e="" data-v-52b6708e="" class="u-count-num"
                                                                  style="font-size: 12px; font-weight: bold; color: rgb(0, 0, 0);">
                                                            <span>{{price($element->amount)}}</span></uni-text>
                                                    </uni-view>
                                                </uni-view>
                                                <uni-view data-v-52b6708e="" class="conitem">
                                                    <uni-view data-v-52b6708e="" class="lab">Recharge Date</uni-view>
                                                    <uni-view data-v-52b6708e="" class="msg">{{$element->created_at}}</uni-view>
                                                </uni-view>
                                            </uni-view>
                                            <uni-view data-v-52b6708e="" class="types2">
                                                <uni-view data-v-52b6708e="" class="lab">Current Status</uni-view>
                                                <uni-view data-v-52b6708e="" class="msg">
                                                    <uni-text data-v-52b6708e="" class="sh"><span style="text-transform: capitalize">{{$element->status}}</span>
                                                    </uni-text>
                                                </uni-view>
                                            </uni-view>
                                        </uni-view>
                                    </uni-view>
                                @endforeach
                            </uni-view>
                        @else

                            <uni-view data-v-7cf13343="" class="nullimg">
                                <svg data-v-7cf13343="" t="1711509772643" viewBox="0 0 1024 1024" version="1.1"
                                     xmlns="http://www.w3.org/2000/svg" p-id="5558" width="48" height="48" class="icon">
                                    <path data-v-7cf13343=""
                                          d="M 479.744 311.296 s 525.312 72.192 537.6 148.48 c 12.288 75.776 -159.744 452.096 -279.552 484.352 c -119.808 32.256 -724.48 -159.744 -726.528 -238.08 c -3.072 -108.032 70.656 -262.656 468.48 -394.752 Z M 371.2 437.248 c -46.592 46.08 158.72 66.048 183.296 62.976 c 24.576 -3.072 105.984 -87.552 89.088 -96.768 c -16.896 -9.216 -185.856 -33.792 -185.856 -33.792 s -39.936 21.504 -86.528 67.584 Z m 274.944 44.032 c -32.768 47.104 155.648 81.92 175.616 88.064 c 19.968 6.144 56.32 -27.648 70.656 -39.936 c 14.848 -12.288 19.968 -30.72 7.68 -48.128 c -12.288 -17.408 -187.392 -68.608 -187.392 -68.608 s -33.792 21.504 -66.56 68.608 Z m -234.496 250.88 c 21.504 36.864 234.496 59.392 234.496 59.392 s 121.856 -111.616 140.288 -161.792 c -27.648 -50.176 -230.4 -75.776 -230.4 -75.776 C 465.92 626.688 423.936 695.296 411.648 732.16 Z m -263.68 -138.24 c -100.864 89.088 93.696 113.152 141.312 110.592 c 37.376 -2.048 159.232 -125.952 168.448 -150.528 c 9.216 -24.576 -168.96 -62.464 -168.96 -62.464 s -39.936 13.312 -140.8 102.4 Z m 200.704 -346.624 s 53.76 88.064 155.648 47.616 c 0 0 -50.176 -75.776 -155.648 -47.616 Z m 68.608 -79.872 s 10.24 70.656 86.528 71.68 c 0.512 0 -10.752 -61.952 -86.528 -71.68 Z m 183.808 0 s -10.24 70.656 -86.528 71.68 c 0 0 11.264 -61.952 86.528 -71.68 Z m 76.288 79.872 s -53.76 88.064 -155.648 47.616 c 0.512 0 50.176 -75.776 155.648 -47.616 Z M 506.368 186.88 s -38.912 -30.208 1.024 -115.2 c 0 0 44.032 75.264 -1.024 115.2 Z"
                                          p-id="5559"></path>
                                </svg>
                            </uni-view>

                            <uni-view data-v-7cf13343="" class="nullmsg">No Data</uni-view>
                        @endif
                    </uni-view>

                    @include('app.layout.manu')

                </uni-view>
            </uni-page-body>
        </uni-page-wrapper>
    </uni-page>
    {{--    <uni-toast data-duration="2000">--}}
    {{--        <div class="uni-toast"><i class="uni-icon_toast uni-icon-success-no-circle"></i>--}}
    {{--            <p class="uni-toast__content"> no more data </p></div>--}}
    {{--    </uni-toast>--}}
</uni-app>
</body>
</html>
