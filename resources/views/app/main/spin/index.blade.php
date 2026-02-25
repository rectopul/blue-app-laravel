<html style="--status-bar-height: 0px; --top-window-height: 0px; --window-left: 0px; --window-right: 0px; --window-margin: 0px; --window-top: calc(var(--top-window-height) + calc(44px + env(safe-area-inset-top))); --window-bottom: 0px;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Treasure</title>
    <meta property="og:title" content="Agridevelop Plus">
    <meta property="og:image" content="{{asset('public')}}/static/login/logo.png">
    <meta name="description"
          content="In the previous Agridevelop project, many people made their first pot of gold through the Agridevelop App. The new AgriDevelop Plus App has just opened registration in March 2024. We will build the best and most long-lasting online money-making application in India. Join AgriDevelop as soon as possible and you will have the best opportunity to make money.	">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, viewport-fit=cover">
    <link rel="stylesheet" href="{{asset('public')}}/static/index.2da1efab.css">
    <link rel="stylesheet" href="{{asset('public')}}/spin.css">
    <style>
        .prompt[data-v-b25ea0a8] {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 321px;
            min-height: 160px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body class="uni-body pages-index-treaurecdk">
<uni-app class="uni-app--maxwidth">
    <uni-page data-page="pages/index/treaurecdk">
        <uni-page-head uni-page-head-type="default">
            <div class="uni-page-head" style="background-color: rgb(13, 165, 97); color: rgb(255, 255, 255);">
                <div class="uni-page-head-hd">
                    <div class="uni-page-head-btn" onclick="window.location.href='{{route('dashboard')}}'"><i class="uni-btn-icon"
                                                      style="color: rgb(255, 255, 255); font-size: 27px;"></i></div>
                    <div class="uni-page-head-ft"></div>
                </div>
                <div class="uni-page-head-bd">
                    <div class="uni-page-head__title" style="font-size: 16px; opacity: 1;"> Treasure</div>
                </div>
                <div class="uni-page-head-ft"></div>
            </div>
            <div class="uni-placeholder"></div>
        </uni-page-head>
        <uni-page-wrapper>
            <uni-page-body>
                <uni-view data-v-0066fdc4="" class="content">
                    <uni-view data-v-0066fdc4="" class="r_box" onclick="openPop()">
                        <uni-view data-v-0066fdc4="" class="msg">Enter Treasure Key</uni-view>
                        <uni-view data-v-0066fdc4="" class="container">
                            <uni-image data-v-0066fdc4="">
                                <div style="background-image: url({{asset('public')}}/static/login/logo.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/login/logo.png" draggable="false"></uni-image>
                            <uni-image data-v-0066fdc4="">
                                <div style="background-image: url({{asset('public')}}/static/login/logo.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/login/logo.png" draggable="false"></uni-image>
                            <uni-image data-v-0066fdc4="">
                                <div style="background-image: url({{asset('public')}}/static/login/logo.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/login/logo.png" draggable="false"></uni-image>
                            <uni-image data-v-0066fdc4="">
                                <div style="background-image: url({{asset('public')}}/static/login/logo.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/login/logo.png" draggable="false"></uni-image>
                            <uni-image data-v-0066fdc4="">
                                <div style="background-image: url({{asset('public')}}/static/login/logo.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/login/logo.png" draggable="false"></uni-image>
                            <uni-image data-v-0066fdc4="">
                                <div style="background-image: url({{asset('public')}}/static/login/logo.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"></div>
                                <img src="{{asset('public')}}/static/login/logo.png" draggable="false"></uni-image>
                        </uni-view>
                    </uni-view>

                    <uni-view data-v-b25ea0a8="" data-v-0066fdc4="" class="prompt-box" style="display: none;">
                        <uni-view data-v-b25ea0a8="" class="prompt">
                            <uni-view data-v-b25ea0a8="" class="prompt-top">
                                <uni-text data-v-b25ea0a8="" class="prompt-title">
                                    <span>Enter Treasure Key</span></uni-text>
                                <div class="msg"></div>
                                <uni-input data-v-b25ea0a8="" class="prompt-input">
                                    <div class="uni-input-wrapper">
                                        <input maxlength="140" step="" name="code" placeholder="Enter Treasure Key" autocomplete="off" type="text" class="uni-input-input"></div>
                                </uni-input>
                            </uni-view>
                            <uni-view data-v-b25ea0a8="" class="prompt-buttons">
                                <uni-button data-v-b25ea0a8="" class="prompt-cancle" style="color: rgb(13, 165, 97);" onclick="closePop()">Cancel</uni-button>
                                <uni-button data-v-b25ea0a8="" class="prompt-confirm rrc" style="background: rgb(13, 165, 97);" onclick="submitBonusConde()">Submit</uni-button>
                            </uni-view>
                        </uni-view>
                    </uni-view>

                    @include('app.layout.manu')
                </uni-view>
            </uni-page-body>
        </uni-page-wrapper>
    </uni-page>
</uni-app>
@include('alert-message')
@include('loading')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function closePop(){
        document.querySelector('.prompt-box').style.display='none';
    }
    function openPop(){
        document.querySelector('.prompt-box').style.display='block';
    }

    function submitBonusConde(){
        var code = document.querySelector('input[name="code"]').value;
        if (code == ''){
            document.querySelector('.msg').innerHTML = 'Enter correct Treasure code';
            return true;
        }
        document.querySelector('.loadingClass').style.display='block';
        var rrc = document.querySelector('.rrc');
        rrc.innerHTML = 'Waiting...'
        $.ajax({
            url: "{{url('submit-bonus-amount')}}"+"/"+code,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                document.querySelector('.loadingClass').style.display='none';
                rrc.innerHTML = 'Submitted';
                document.querySelector('.msg').innerHTML = res.message;
                document.querySelector('input[name="code"]').value = ''
            }
        });
    }
</script>
</body>
</html>
