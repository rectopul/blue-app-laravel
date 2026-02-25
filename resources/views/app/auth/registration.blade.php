<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Junte-se á - {{ env('APP_NAME') }}</title>
    @include('partials.tailwind')

</head>

<body>
    <div class="flex min-h-screen w-full items-center justify-center bg-elm-50 max-md:px-4 py-10">

        <div
            class="w-full max-w-[480px] overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 hover:shadow-elm-200/50">

            <div class="h-2 bg-elm-600 w-full"></div>

            <div class="p-8 md:p-10">
                <div class="mb-8 text-center">
                    <div class="mb-4">
                        <img class="w-20 h-20 object-cover mx-auto"
                            src="{{ asset(main_root() . '/assets/img/lotus-ativos-logo.png') }}"
                            alt="{{ env('APP_NAME') }}">
                    </div>
                    <h1 class="text-3xl font-extrabold text-elm-900 tracking-tight">
                        Crie sua Conta
                    </h1>
                    <p class="mt-2 text-sm text-elm-600/80">
                        Junte-se à Lótus e comece a construir seu patrimônio.
                    </p>
                </div>

                <form class="space-y-5" method="POST" action="{{ route('register.submit') }}">
                    @csrf

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
                            <input type="tel" id="phone" name="phone"
                                class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                placeholder="(00) 00000-0000" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="password"
                                class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">Senha</label>
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
                                    placeholder="********" required />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="confirm-password"
                                class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">Confirmar</label>
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
                                <input type="password" id="confirm-password" name="confirm-password"
                                    class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm"
                                    placeholder="********" required />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="invite-code"
                            class="text-xs font-bold uppercase tracking-wider text-elm-800 ml-1">Código de
                            Convite</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-elm-400 group-focus-within:text-elm-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zM7 10a1 1 0 01-1 1H5a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM10 11a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input type="text" id="invite-code" name="ref_by"
                                value="{{ request()->get('inviteCode') }}"
                                class="block w-full rounded-xl border-2 border-elm-100 bg-elm-50/30 py-3.5 pl-11 pr-4 text-elm-900 placeholder-elm-300 transition-all focus:border-elm-500 focus:bg-white focus:ring-0 sm:text-sm uppercase font-semibold tracking-widest"
                                placeholder="CÓDIGO OPCIONAL" />
                        </div>
                    </div>

                    <div class="flex items-start px-1 pt-2">
                        <div class="flex h-5 items-center">
                            <input id="accept-terms" name="accept-terms" type="checkbox" required
                                class="h-4 w-4 rounded border-elm-300 text-elm-600 focus:ring-elm-500 cursor-pointer" />
                        </div>
                        <label for="accept-terms" class="ml-3 text-sm text-elm-700 leading-tight">
                            Eu aceito os <a href="#"
                                class="font-bold text-buttermilk-600 hover:text-buttermilk-700 transition-colors">Termos
                                e Condições de Uso</a> e a Política de Privacidade.
                        </label>
                    </div>

                    <button type="submit"
                        class="group relative flex w-full items-center justify-center overflow-hidden rounded-xl bg-elm-600 px-8 py-4 text-white shadow-lg transition-all hover:bg-elm-700 hover:shadow-elm-200 active:scale-[0.98]">
                        <span class="relative z-10 font-bold tracking-wide">CRIAR MINHA CONTA</span>
                        <div
                            class="absolute inset-0 z-0 bg-gradient-to-r from-elm-500 to-elm-700 opacity-0 transition-opacity group-hover:opacity-100">
                        </div>
                    </button>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-elm-600">
                            Já possui uma conta ativa?
                            <a href="{{ route('login') }}"
                                class="ml-1 font-bold text-buttermilk-600 hover:text-buttermilk-700 underline-offset-4 hover:underline transition-all">
                                Faça Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
