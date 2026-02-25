<style>
    @import url(https://fonts.googleapis.com/css2?family=Lato&display=swap);
    @import url(https://fonts.googleapis.com/css2?family=Open+Sans&display=swap);
</style>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
    @theme {

        --color-elm-50: #f2fbfa;
        --color-elm-100: #d3f4f0;
        --color-elm-200: #a8e7e1;
        --color-elm-300: #74d4cf;
        --color-elm-400: #47bab6;
        --color-elm-500: #2e9e9d;
        --color-elm-600: #227d7e;
        --color-elm-700: #1f6566;
        --color-elm-800: #1d5152;
        --color-elm-900: #1c4445;
        --color-elm-950: #0b2528;

        --color-buttermilk-50: #fffbeb;
        --color-buttermilk-100: #fdebad;
        --color-buttermilk-200: #fce38b;
        --color-buttermilk-300: #fbce4e;
        --color-buttermilk-400: #fab925;
        --color-buttermilk-500: #f3980d;
        --color-buttermilk-600: #d87207;
        --color-buttermilk-700: #b34f0a;
        --color-buttermilk-800: #913d0f;
        --color-buttermilk-900: #773210;
        --color-buttermilk-950: #451803;


    }

    /* Animações customizadas */
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(0);
            opacity: 1;
        }

        to {
            transform: translateY(-100%);
            opacity: 0;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes checkmark {
        0% {
            stroke-dashoffset: 100;
        }

        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-enter {
        animation: slideDown 0.3s ease-out forwards;
    }

    .modal-exit {
        animation: slideUp 0.3s ease-in forwards;
    }

    .backdrop-enter {
        animation: fadeIn 0.3s ease-out forwards;
    }

    .backdrop-exit {
        animation: fadeOut 0.3s ease-in forwards;
    }

    .spinner {
        animation: spin 1s linear infinite;
    }

    .checkmark {
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: checkmark 0.6s ease-in-out forwards;
    }

    .success-icon {
        animation: scaleIn 0.5s ease-out forwards;
    }

    .animate-pulse-btn {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulseBtn {
        0% {
            box-shadow: 0 0 0 0 rgba(47, 102, 163, .4)
        }

        70% {
            box-shadow: 0 0 2.133333vw 1.066667vw rgba(47, 102, 163, .133)
        }

        100% {
            box-shadow: 0 0 0 0 rgba(47, 102, 163, .4)
        }
    }

    @keyframes slideUpFromBottom {
        from {
            transform: translateY(100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideDownToBottom {
        from {
            transform: translateY(0);
            opacity: 1;
        }

        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }

    .modal-enter-bottom {
        animation: slideUpFromBottom 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .modal-exit-bottom {
        animation: slideDownToBottom 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @utility animate-pulse-btn {
        animation: pulseBtn 2s infinite;
    }

    @keyframes scroll {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    .animate-scroll {
        display: inline-flex;
        animation: scroll 15s linear infinite;
    }
</style>
<style>
    #webcrumbs {
        background-image: url("{{ asset(main_root() . '/assets/img/background-bg.jpg') }}");
        background-position: center;
        background-size: 100%;
        background-repeat: no-repeat;
    }

    @media screen and (max-width: 700px) {
        #webcrumbs {
            background-image: url("{{ asset(main_root() . '/assets/img/background-bg.jpg') }}");
            background-position: center;
            background-size: auto 1000px;
            background-repeat: no-repeat;
        }
    }
</style>
