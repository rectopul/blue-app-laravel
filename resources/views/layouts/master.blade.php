<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($title))
        <title>{{ $title }} - {{ env('APP_NAME') }}</title>
    @else
        <title>Bem vindo a - {{ env('APP_NAME') }}</title>
    @endif
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Lato&display=swap);
        @import url(https://fonts.googleapis.com/css2?family=Open+Sans&display=swap);
    </style>
    @yield('styles')
    <style>
        *,
        ::before,
        ::after {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-gradient-from-position: ;
            --tw-gradient-via-position: ;
            --tw-gradient-to-position: ;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
            --tw-contain-size: ;
            --tw-contain-layout: ;
            --tw-contain-paint: ;
            --tw-contain-style:
        }

        ::backdrop {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-gradient-from-position: ;
            --tw-gradient-via-position: ;
            --tw-gradient-to-position: ;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
            --tw-contain-size: ;
            --tw-contain-layout: ;
            --tw-contain-paint: ;
            --tw-contain-style:
        }

        /* ! tailwindcss v3.4.16 | MIT License | https://tailwindcss.com */
        *,
        ::after,
        ::before {
            box-sizing: border-box;
            border-width: 0;
            border-style: solid;
            border-color: #e5e7eb
        }

        ::after,
        ::before {
            --tw-content: ''
        }

        :host,
        html {
            line-height: 1.5;
            -webkit-text-size-adjust: 100%;
            -moz-tab-size: 4;
            tab-size: 4;
            font-family: Open Sans, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-feature-settings: normal;
            font-variation-settings: normal;
            -webkit-tap-highlight-color: transparent
        }

        body {
            margin: 0;
            line-height: inherit
        }

        hr {
            height: 0;
            color: inherit;
            border-top-width: 1px
        }

        abbr:where([title]) {
            -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-size: inherit;
            font-weight: inherit
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        b,
        strong {
            font-weight: bolder
        }

        code,
        kbd,
        pre,
        samp {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-feature-settings: normal;
            font-variation-settings: normal;
            font-size: 1em
        }

        small {
            font-size: 80%
        }

        sub,
        sup {
            font-size: 75%;
            line-height: 0;
            position: relative;
            vertical-align: baseline
        }

        sub {
            bottom: -.25em
        }

        sup {
            top: -.5em
        }

        table {
            text-indent: 0;
            border-color: inherit;
            border-collapse: collapse
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            font-family: inherit;
            font-feature-settings: inherit;
            font-variation-settings: inherit;
            font-size: 100%;
            font-weight: inherit;
            line-height: inherit;
            letter-spacing: inherit;
            color: inherit;
            margin: 0;
            padding: 0
        }

        button,
        select {
            text-transform: none
        }

        button,
        input:where([type=button]),
        input:where([type=reset]),
        input:where([type=submit]) {
            -webkit-appearance: button;
            background-color: transparent;
            background-image: none
        }

        :-moz-focusring {
            outline: auto
        }

        :-moz-ui-invalid {
            box-shadow: none
        }

        progress {
            vertical-align: baseline
        }

        ::-webkit-inner-spin-button,
        ::-webkit-outer-spin-button {
            height: auto
        }

        [type=search] {
            -webkit-appearance: textfield;
            outline-offset: -2px
        }

        ::-webkit-search-decoration {
            -webkit-appearance: none
        }

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            font: inherit
        }

        summary {
            display: list-item
        }

        blockquote,
        dd,
        dl,
        figure,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        hr,
        p,
        pre {
            margin: 0
        }

        fieldset {
            margin: 0;
            padding: 0
        }

        legend {
            padding: 0
        }

        menu,
        ol,
        ul {
            list-style: none;
            margin: 0;
            padding: 0
        }

        dialog {
            padding: 0
        }

        textarea {
            resize: vertical
        }

        input::placeholder,
        textarea::placeholder {
            opacity: 1;
            color: #9ca3af
        }

        [role=button],
        button {
            cursor: pointer
        }

        :disabled {
            cursor: default
        }

        audio,
        canvas,
        embed,
        iframe,
        img,
        object,
        svg,
        video {
            display: block;
            vertical-align: middle
        }

        img,
        video {
            max-width: 100%;
            height: auto
        }

        [hidden]:where(:not([hidden=until-found])) {
            display: none
        }

        #webcrumbs .fixed {
            position: fixed
        }

        #webcrumbs .absolute {
            position: absolute
        }

        #webcrumbs .relative {
            position: relative
        }

        #webcrumbs .inset-0 {
            inset: 0px
        }

        #webcrumbs .-bottom-2 {
            bottom: -8px
        }

        #webcrumbs .-left-8 {
            left: -32px
        }

        #webcrumbs .-right-8 {
            right: -32px
        }

        #webcrumbs .-top-4 {
            top: -16px
        }

        #webcrumbs .bottom-0 {
            bottom: 0px
        }

        #webcrumbs .left-0 {
            left: 0px
        }

        #webcrumbs .right-0 {
            right: 0px
        }

        #webcrumbs .right-4 {
            right: 16px
        }

        #webcrumbs .top-4 {
            top: 16px
        }

        #webcrumbs .z-10 {
            z-index: 10
        }

        #webcrumbs .z-50 {
            z-index: 50
        }

        #webcrumbs .mx-4 {
            margin-left: 16px;
            margin-right: 16px
        }

        #webcrumbs .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        #webcrumbs .-ml-1 {
            margin-left: -4px
        }

        #webcrumbs .mb-1 {
            margin-bottom: 4px
        }

        #webcrumbs .mb-4 {
            margin-bottom: 16px
        }

        #webcrumbs .mb-5 {
            margin-bottom: 20px
        }

        #webcrumbs .mb-6 {
            margin-bottom: 24px
        }

        #webcrumbs .mb-8 {
            margin-bottom: 32px
        }

        #webcrumbs .ml-1 {
            margin-left: 4px
        }

        #webcrumbs .ml-3 {
            margin-left: 12px
        }

        #webcrumbs .mr-1 {
            margin-right: 4px
        }

        #webcrumbs .mr-2 {
            margin-right: 8px
        }

        #webcrumbs .mr-3 {
            margin-right: 12px
        }

        #webcrumbs .mr-4 {
            margin-right: 16px
        }

        #webcrumbs .mt-0\.5 {
            margin-top: 2px
        }

        #webcrumbs .mt-1 {
            margin-top: 4px
        }

        #webcrumbs .mt-2 {
            margin-top: 8px
        }

        #webcrumbs .mt-3 {
            margin-top: 12px
        }

        #webcrumbs .mt-4 {
            margin-top: 16px
        }

        #webcrumbs .mt-6 {
            margin-top: 24px
        }

        #webcrumbs .inline-block {
            display: inline-block
        }

        #webcrumbs .inline {
            display: inline
        }

        #webcrumbs .flex {
            display: flex
        }

        #webcrumbs .grid {
            display: grid
        }

        #webcrumbs .h-1 {
            height: 4px
        }

        #webcrumbs .h-10 {
            height: 40px
        }

        #webcrumbs .h-12 {
            height: 48px
        }

        #webcrumbs .h-16 {
            height: 64px
        }

        #webcrumbs .h-2 {
            height: 8px
        }

        #webcrumbs .h-24 {
            height: 96px
        }

        #webcrumbs .h-3 {
            height: 12px
        }

        #webcrumbs .h-4 {
            height: 16px
        }

        #webcrumbs .h-5 {
            height: 20px
        }

        #webcrumbs .h-6 {
            height: 24px
        }

        #webcrumbs .h-8 {
            height: 32px
        }

        #webcrumbs .h-full {
            height: 100%
        }

        #webcrumbs .min-h-screen {
            min-height: 100vh
        }

        #webcrumbs .w-10 {
            width: 40px
        }

        #webcrumbs .w-12 {
            width: 48px
        }

        #webcrumbs .w-16 {
            width: 64px
        }

        #webcrumbs .w-2 {
            width: 8px
        }

        #webcrumbs .w-24 {
            width: 96px
        }

        #webcrumbs .w-3 {
            width: 12px
        }

        #webcrumbs .w-4 {
            width: 16px
        }

        #webcrumbs .w-5 {
            width: 20px
        }

        #webcrumbs .w-6 {
            width: 24px
        }

        #webcrumbs .w-8 {
            width: 32px
        }

        #webcrumbs .w-\[100px\] {
            width: 100px
        }

        #webcrumbs .w-\[420px\] {
            width: 420px
        }

        #webcrumbs .w-full {
            width: 100%
        }

        #webcrumbs .max-w-md {
            max-width: 28rem
        }

        #webcrumbs .max-w-xs {
            max-width: 20rem
        }

        #webcrumbs .flex-1 {
            flex: 1 1 0%
        }

        #webcrumbs .flex-shrink-0 {
            flex-shrink: 0
        }

        #webcrumbs .scale-100 {
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        #webcrumbs .scale-95 {
            --tw-scale-x: .95;
            --tw-scale-y: .95;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        #webcrumbs .transform {
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(-25%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1)
            }

            50% {
                transform: none;
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1)
            }
        }

        #webcrumbs .animate-bounce {
            animation: bounce 1s infinite
        }

        @keyframes pulse {
            50% {
                opacity: .5
            }
        }

        #webcrumbs .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        #webcrumbs .animate-spin {
            animation: spin 1s linear infinite
        }

        #webcrumbs .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }

        #webcrumbs .flex-col {
            flex-direction: column
        }

        #webcrumbs .items-start {
            align-items: flex-start
        }

        #webcrumbs .items-center {
            align-items: center
        }

        #webcrumbs .justify-end {
            justify-content: flex-end
        }

        #webcrumbs .justify-center {
            justify-content: center
        }

        #webcrumbs .justify-between {
            justify-content: space-between
        }

        #webcrumbs .gap-1 {
            gap: 4px
        }

        #webcrumbs .gap-2 {
            gap: 8px
        }

        #webcrumbs .gap-3 {
            gap: 12px
        }

        #webcrumbs .gap-4 {
            gap: 16px
        }

        #webcrumbs :is(.space-x-2 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-x-reverse: 0;
            margin-right: calc(8px * var(--tw-space-x-reverse));
            margin-left: calc(8px * calc(1 - var(--tw-space-x-reverse)))
        }

        #webcrumbs :is(.space-x-3 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-x-reverse: 0;
            margin-right: calc(12px * var(--tw-space-x-reverse));
            margin-left: calc(12px * calc(1 - var(--tw-space-x-reverse)))
        }

        #webcrumbs :is(.space-x-6 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-x-reverse: 0;
            margin-right: calc(24px * var(--tw-space-x-reverse));
            margin-left: calc(24px * calc(1 - var(--tw-space-x-reverse)))
        }

        #webcrumbs :is(.space-y-2 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-y-reverse: 0;
            margin-top: calc(8px * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(8px * var(--tw-space-y-reverse))
        }

        #webcrumbs :is(.space-y-3 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-y-reverse: 0;
            margin-top: calc(12px * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(12px * var(--tw-space-y-reverse))
        }

        #webcrumbs :is(.space-y-5 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-y-reverse: 0;
            margin-top: calc(20px * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(20px * var(--tw-space-y-reverse))
        }

        #webcrumbs :is(.space-y-6 > :not([hidden]) ~ :not([hidden])) {
            --tw-space-y-reverse: 0;
            margin-top: calc(24px * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(24px * var(--tw-space-y-reverse))
        }

        #webcrumbs .overflow-auto {
            overflow: auto
        }

        #webcrumbs .overflow-hidden {
            overflow: hidden
        }

        #webcrumbs .rounded-2xl {
            border-radius: 48px
        }

        #webcrumbs .rounded-full {
            border-radius: 9999px
        }

        #webcrumbs .rounded-lg {
            border-radius: 24px
        }

        #webcrumbs .rounded-md {
            border-radius: 18px
        }

        #webcrumbs .rounded-xl {
            border-radius: 36px
        }

        #webcrumbs .border {
            border-width: 1px
        }

        #webcrumbs .border-2 {
            border-width: 2px
        }

        #webcrumbs .border-t {
            border-top-width: 1px
        }

        #webcrumbs .border-blue-400 {
            --tw-border-opacity: 1;
            border-color: rgb(96 165 250 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .border-gray-100 {
            --tw-border-opacity: 1;
            border-color: rgb(243 244 246 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .border-gray-200 {
            --tw-border-opacity: 1;
            border-color: rgb(229 231 235 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .border-gray-300 {
            --tw-border-opacity: 1;
            border-color: rgb(209 213 219 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .border-red-500 {
            --tw-border-opacity: 1;
            border-color: rgb(239 68 68 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .border-transparent {
            border-color: transparent
        }

        #webcrumbs .border-white {
            --tw-border-opacity: 1;
            border-color: rgb(255 255 255 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .border-red-200 {
            --tw-border-opacity: 1;
            border-color: rgb(254 202 202 / var(--tw-border-opacity, 1))
        }

        #webcrumbs .bg-black {
            --tw-bg-opacity: 1;
            background-color: rgb(0 0 0 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-blue-400 {
            --tw-bg-opacity: 1;
            background-color: rgb(96 165 250 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-blue-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(239 246 255 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-blue-500 {
            --tw-bg-opacity: 1;
            background-color: rgb(59 130 246 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-gray-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(249 250 251 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-green-500 {
            --tw-bg-opacity: 1;
            background-color: rgb(34 197 94 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-orange-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(255 247 237 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-purple-500 {
            --tw-bg-opacity: 1;
            background-color: rgb(168 85 247 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-red-500 {
            --tw-bg-opacity: 1;
            background-color: rgb(239 68 68 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-white {
            --tw-bg-opacity: 1;
            background-color: rgb(255 255 255 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-red-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(254 242 242 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .bg-opacity-10 {
            --tw-bg-opacity: 0.1
        }

        #webcrumbs .bg-opacity-20 {
            --tw-bg-opacity: 0.2
        }

        #webcrumbs .bg-opacity-50 {
            --tw-bg-opacity: 0.5
        }

        #webcrumbs .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops))
        }

        #webcrumbs .from-green-500 {
            --tw-gradient-from: #22c55e var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(34 197 94 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
        }

        #webcrumbs .from-red-400 {
            --tw-gradient-from: #f87171 var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(248 113 113 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
        }

        #webcrumbs .from-red-500 {
            --tw-gradient-from: #ef4444 var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(239 68 68 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
        }

        #webcrumbs .via-blue-500 {
            --tw-gradient-to: rgb(59 130 246 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), #3b82f6 var(--tw-gradient-via-position), var(--tw-gradient-to)
        }

        #webcrumbs .to-green-600 {
            --tw-gradient-to: #16a34a var(--tw-gradient-to-position)
        }

        #webcrumbs .to-purple-500 {
            --tw-gradient-to: #a855f7 var(--tw-gradient-to-position)
        }

        #webcrumbs .to-red-600 {
            --tw-gradient-to: #dc2626 var(--tw-gradient-to-position)
        }

        #webcrumbs .object-cover {
            object-fit: cover
        }

        #webcrumbs .p-2 {
            padding: 8px
        }

        #webcrumbs .p-3 {
            padding: 12px
        }

        #webcrumbs .p-4 {
            padding: 16px
        }

        #webcrumbs .p-5 {
            padding: 20px
        }

        #webcrumbs .p-6 {
            padding: 24px
        }

        #webcrumbs .px-2 {
            padding-left: 8px;
            padding-right: 8px
        }

        #webcrumbs .px-4 {
            padding-left: 16px;
            padding-right: 16px
        }

        #webcrumbs .px-6 {
            padding-left: 24px;
            padding-right: 24px
        }

        #webcrumbs .py-1 {
            padding-top: 4px;
            padding-bottom: 4px
        }

        #webcrumbs .py-2 {
            padding-top: 8px;
            padding-bottom: 8px
        }

        #webcrumbs .py-2\.5 {
            padding-top: 10px;
            padding-bottom: 10px
        }

        #webcrumbs .py-3 {
            padding-top: 12px;
            padding-bottom: 12px
        }

        #webcrumbs .py-4 {
            padding-top: 16px;
            padding-bottom: 16px
        }

        #webcrumbs .pl-6 {
            padding-left: 24px
        }

        #webcrumbs .pt-0 {
            padding-top: 0px
        }

        #webcrumbs .pt-2 {
            padding-top: 8px
        }

        #webcrumbs .text-center {
            text-align: center
        }

        #webcrumbs .font-sans {
            font-family: Open Sans, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
        }

        #webcrumbs .text-2xl {
            font-size: 24px;
            line-height: 31.200000000000003px
        }

        #webcrumbs .text-lg {
            font-size: 18px;
            line-height: 27px
        }

        #webcrumbs .text-sm {
            font-size: 14px;
            line-height: 21px
        }

        #webcrumbs .text-xl {
            font-size: 20px;
            line-height: 28px
        }

        #webcrumbs .text-xs {
            font-size: 12px;
            line-height: 19.200000000000003px
        }

        #webcrumbs .font-bold {
            font-weight: 700
        }

        #webcrumbs .font-medium {
            font-weight: 500
        }

        #webcrumbs .font-semibold {
            font-weight: 600
        }

        #webcrumbs .leading-relaxed {
            line-height: 1.625
        }

        #webcrumbs .text-blue-600 {
            --tw-text-opacity: 1;
            color: rgb(37 99 235 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-gray-400 {
            --tw-text-opacity: 1;
            color: rgb(156 163 175 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-gray-500 {
            --tw-text-opacity: 1;
            color: rgb(107 114 128 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-gray-600 {
            --tw-text-opacity: 1;
            color: rgb(75 85 99 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-gray-700 {
            --tw-text-opacity: 1;
            color: rgb(55 65 81 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-gray-800 {
            --tw-text-opacity: 1;
            color: rgb(31 41 55 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-green-600 {
            --tw-text-opacity: 1;
            color: rgb(22 163 74 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-purple-600 {
            --tw-text-opacity: 1;
            color: rgb(147 51 234 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-red-500 {
            --tw-text-opacity: 1;
            color: rgb(239 68 68 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-red-600 {
            --tw-text-opacity: 1;
            color: rgb(220 38 38 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-white {
            --tw-text-opacity: 1;
            color: rgb(255 255 255 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .text-red-800 {
            --tw-text-opacity: 1;
            color: rgb(153 27 27 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .opacity-25 {
            opacity: 0.25
        }

        #webcrumbs .opacity-75 {
            opacity: 0.75
        }

        #webcrumbs .opacity-80 {
            opacity: 0.8
        }

        #webcrumbs .opacity-100 {
            opacity: 1
        }

        #webcrumbs .opacity-0 {
            opacity: 0
        }

        #webcrumbs .shadow-2xl {
            --tw-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --tw-shadow-colored: 0 25px 50px -12px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .shadow-lg {
            --tw-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .shadow-md {
            --tw-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 4px 6px -1px var(--tw-shadow-color), 0 2px 4px -2px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .shadow-sm {
            --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .shadow-xl {
            --tw-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .backdrop-blur-sm {
            --tw-backdrop-blur: blur(4px);
            -webkit-backdrop-filter: var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia);
            backdrop-filter: var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia)
        }

        #webcrumbs .transition {
            transition-property: color, background-color, border-color, fill, stroke, opacity, box-shadow, transform, filter, -webkit-text-decoration-color, -webkit-backdrop-filter;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter, -webkit-text-decoration-color, -webkit-backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        #webcrumbs .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        #webcrumbs .transition-colors {
            transition-property: color, background-color, border-color, fill, stroke, -webkit-text-decoration-color;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, -webkit-text-decoration-color;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        #webcrumbs .transition-shadow {
            transition-property: box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        #webcrumbs .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms
        }

        #webcrumbs .duration-200 {
            transition-duration: 200ms
        }

        #webcrumbs .duration-300 {
            transition-duration: 300ms
        }

        #webcrumbs .ease-in {
            transition-timing-function: cubic-bezier(0.4, 0, 1, 1)
        }

        #webcrumbs .hover\:rotate-90:hover {
            --tw-rotate: 90deg;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        #webcrumbs .hover\:scale-110:hover {
            --tw-scale-x: 1.1;
            --tw-scale-y: 1.1;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        #webcrumbs .hover\:scale-\[1\.02\]:hover {
            --tw-scale-x: 1.02;
            --tw-scale-y: 1.02;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        #webcrumbs .hover\:bg-blue-600:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(37 99 235 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .hover\:bg-gray-50:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(249 250 251 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .hover\:bg-green-600:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(22 163 74 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .hover\:bg-orange-50:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(255 247 237 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .hover\:bg-red-600:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(220 38 38 / var(--tw-bg-opacity, 1))
        }

        #webcrumbs .hover\:bg-opacity-30:hover {
            --tw-bg-opacity: 0.3
        }

        #webcrumbs .hover\:from-green-600:hover {
            --tw-gradient-from: #16a34a var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(22 163 74 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
        }

        #webcrumbs .hover\:to-green-700:hover {
            --tw-gradient-to: #15803d var(--tw-gradient-to-position)
        }

        #webcrumbs .hover\:text-gray-200:hover {
            --tw-text-opacity: 1;
            color: rgb(229 231 235 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .hover\:text-gray-700:hover {
            --tw-text-opacity: 1;
            color: rgb(55 65 81 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .hover\:text-orange-700:hover {
            --tw-text-opacity: 1;
            color: rgb(194 65 12 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .hover\:text-red-500:hover {
            --tw-text-opacity: 1;
            color: rgb(239 68 68 / var(--tw-text-opacity, 1))
        }

        #webcrumbs .hover\:underline:hover {
            -webkit-text-decoration-line: underline;
            text-decoration-line: underline
        }

        #webcrumbs .hover\:shadow-lg:hover {
            --tw-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .hover\:shadow-md:hover {
            --tw-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 4px 6px -1px var(--tw-shadow-color), 0 2px 4px -2px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .hover\:shadow-xl:hover {
            --tw-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)
        }

        #webcrumbs .focus\:outline-none:focus {
            outline: 2px solid transparent;
            outline-offset: 2px
        }

        #webcrumbs .focus\:ring-2:focus {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000)
        }

        #webcrumbs .focus\:ring-red-500:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(239 68 68 / var(--tw-ring-opacity, 1))
        }

        #webcrumbs .focus\:ring-offset-2:focus {
            --tw-ring-offset-width: 2px
        }

        #webcrumbs .disabled\:opacity-40:disabled {
            opacity: 0.4
        }

        #webcrumbs :is(.group:hover .group-hover\:translate-x-1) {
            --tw-translate-x: 4px;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        #webcrumbs :is(.group:hover .group-hover\:scale-110) {
            --tw-scale-x: 1.1;
            --tw-scale-y: 1.1;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        @media (min-width: 640px) {
            #webcrumbs .sm\:flex-row {
                flex-direction: row
            }
        }

        @media (min-width: 768px) {
            #webcrumbs .md\:mx-0 {
                margin-left: 0px;
                margin-right: 0px
            }
        }
    </style>
</head>

<body>
    <div id="webcrumbs" style="padding-bottom: 60px;">
        @yield('content')
        @include('partials.nav')
        @if (\Route::is('dashboard'))
            @include('partials.popup')
        @endif
    </div>
    @yield('scripts')
</body>

</html>
