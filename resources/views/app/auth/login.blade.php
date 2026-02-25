<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - {{ env('APP_NAME') }}</title>
    @include('partials.tailwind')
</head>

<body>
    <div id="webcrumbs">
        <div class="flex min-h-screen w-full items-center justify-center bg-elm-50 max-md:px-4">

            <div
                class="w-full max-w-[440px] overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 hover:shadow-elm-200/50">

                <div class="h-2 bg-elm-600 w-full"></div>

                <div class="p-8 md:p-10">
                    <div class="mb-10 text-center">
                        <div
                            class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-elm-100 text-elm-600 success-icon">
                            <img src="{{ asset(main_root() . '/assets/img/lotus-ativos-logo.png') }}"
                                alt="lotus ativus">
                        </div>
                        <h1 class="text-3xl font-extrabold text-elm-900 tracking-tight">
                            Lótus Ativos
                        </h1>
                        <p class="mt-2 text-sm text-elm-600/80">
                            Sua jornada para a liberdade financeira começa aqui.
                        </p>
                    </div>

                    <form class="space-y-5" action="{{ route('login.submit') }}" method="POST">
                        @csrf

                        @if (session('error'))
                            <div
                                class="animate-pulse-btn flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="space-y-1.5">
                            <label for="phone"
                                class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">Telefone</label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-400 group-focus-within:text-elm-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                        </path>
                                    </svg>
                                </span>
                                <input type="tel" id="phone" name="auth"
                                    class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                    placeholder="(00) 00000-0000" autocomplete="off" required />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between px-1">
                                <label for="password"
                                    class="text-xs font-bold uppercase tracking-wider text-elm-800">Senha</label>
                                <a href="#"
                                    class="text-xs font-semibold text-buttermilk-600 hover:text-buttermilk-700 transition-colors">Esqueceu?</a>
                            </div>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-400 group-focus-within:text-elm-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <input type="password" id="password" name="password"
                                    class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                    placeholder="Sua senha secreta" autocomplete="current-password" required />
                            </div>
                        </div>

                        <div class="flex items-center px-1">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 rounded border-elm-300 text-elm-600 focus:ring-elm-500 cursor-pointer" />
                            <label for="remember-me" class="ml-2 block text-sm text-elm-700 cursor-pointer select-none">
                                Manter conectado
                            </label>
                        </div>

                        <button type="submit"
                            class="group relative flex w-full items-center justify-center overflow-hidden rounded-xl bg-elm-600 px-8 py-4 text-white shadow-lg transition-all hover:bg-elm-700 hover:shadow-elm-200 active:scale-[0.98]">
                            <span class="relative z-10 font-bold tracking-wide">ACESSAR MINHA CONTA</span>
                            <div
                                class="absolute inset-0 z-0 bg-gradient-to-r from-elm-500 to-elm-700 opacity-0 transition-opacity group-hover:opacity-100">
                            </div>
                        </button>

                        <div class="mt-8 text-center">
                            <p class="text-sm text-elm-600">
                                Não faz parte da Lótus?
                                <a href="{{ route('register') }}"
                                    class="ml-1 font-bold text-buttermilk-600 hover:text-buttermilk-700 underline-offset-4 hover:underline">
                                    Abra sua conta grátis
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
