<html style="--status-bar-height: 0px; --top-window-height: 0px; --window-left: 0px; --window-right: 0px; --window-margin: 0px; --window-top: calc(var(--top-window-height) + calc(44px + env(safe-area-inset-top))); --window-bottom: 0px;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Modify Bank Account</title>
    <meta property="og:title" content="Agridevelop Plus">
    <meta property="og:image" content="static/login/logo.png">
    <meta name="description"
          content="In the previous Agridevelop project, many people made their first pot of gold through the Agridevelop App. The new AgriDevelop Plus App has just opened registration in March 2024. We will build the best and most long-lasting online money-making application in India. Join AgriDevelop as soon as possible and you will have the best opportunity to make money.	">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, viewport-fit=cover">
    <link rel="stylesheet" href="{{asset('public')}}/static/index.2da1efab.css">
    <link rel="stylesheet" href="{{asset('public')}}/bank.css">
    <style>
        select{
            border: none;
        }
        select:focus-visible{
            border: none;
            outline: none;
        }
    </style>
</head>
<body class="uni-body pages-my-card">
<form action="{{route('setup.gateway.submit')}}" method="post">
    @csrf
<uni-app class="uni-app--maxwidth">
    <uni-page data-page="pages/my/card">
        <uni-page-head uni-page-head-type="default">
            <div class="uni-page-head" style="background-color: rgb(13, 165, 97); color: rgb(255, 255, 255);">
                <div class="uni-page-head-hd">
                    <div class="uni-page-head-btn" onclick="window.location.href='{{route('user.withdraw')}}'"><i class="uni-btn-icon"
                                                      style="color: rgb(255, 255, 255); font-size: 27px;"></i></div>
                    <div class="uni-page-head-ft"></div>
                </div>
                <div class="uni-page-head-bd">
                    <div class="uni-page-head__title" style="font-size: 16px; opacity: 1;"> Modify Bank Account
                    </div>
                </div>
                <div class="uni-page-head-ft"></div>
            </div>
            <div class="uni-placeholder"></div>
        </uni-page-head>
        <uni-page-wrapper>
            <uni-page-body>
                <uni-view data-v-e547158c="" class="content">
                    <uni-view data-v-e547158c="" class="card_box" style="display: none;">
                        <uni-view data-v-e547158c="" class="card">
                            <uni-image data-v-e547158c="" class="sim">
                                <div style="background-image: url({{asset('public')}}/static/img/card.3f0e19d5.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/img/card.3f0e19d5.png" draggable="false"></uni-image>
                            <uni-view data-v-e547158c="" class="card_a"></uni-view>
                            <uni-view data-v-e547158c="" class="card_number"></uni-view>
                            <uni-view data-v-e547158c="" class="card_name">Real Name ：</uni-view>
                        </uni-view>
                    </uni-view>
                    <uni-view data-v-e547158c="" class="itembox">
                        <uni-view data-v-e547158c="" class="input_con">
                            <uni-view data-v-e547158c="" class="input_lable">Real Name</uni-view>
                            <uni-input data-v-e547158c="" class="input_box">
                                <div class="uni-input-wrapper">
                                    <input maxlength="40" step="" type="text"
                                           placeholder="Enter your real name"
                                           name="name"
                                           class="uni-input-input"></div>
                            </uni-input>
                        </uni-view>

                        <uni-view data-v-e547158c="" class="input_con" style="position: relative;">
                            <uni-view data-v-e547158c="" class="input_lable">Bank code</uni-view>
                            <uni-input data-v-e547158c="" class="input_box">
                                <div class="uni-input-wrapper">
                                    <select name="gateway_method" id="gateway_method">
                                        <option value="">Enter Bank code</option>
                                        @foreach(\App\Models\PaymentMethod::get() as $element)
                                        <option value="{{$element->name}}">{{$element->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </uni-input>
                        </uni-view>
                        <uni-view data-v-e547158c="" class="input_con">
                            <uni-view data-v-e547158c="" class="input_lable">Bank Account</uni-view>
                            <uni-input data-v-e547158c="" class="input_box">
                                <div class="uni-input-wrapper">
                                    <div class="uni-input-placeholder input-placeholder" data-v-e547158c="">
                                    </div>
                                    <input placeholder="Enter Bank Account number"
                                           name="gateway_number" type="text" class="uni-input-input">
                                </div>
                            </uni-input>
                        </uni-view>

                        <uni-view data-v-e547158c="" class="my_btn" onclick="submitBank()">Confirm</uni-view>
                    </uni-view>

                    @include('app.layout.manu')
                </uni-view>
            </uni-page-body>
        </uni-page-wrapper>
    </uni-page>
</uni-app>
</form>
@include('alert-message')
@include('loading')
<script>
    function submitBank(){
        document.querySelector('.loadingClass').style.display='block';
        document.querySelector('form').submit();
    }
</script>
</body>
</html>
