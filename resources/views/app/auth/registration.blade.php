@extends('layouts.blueapp')

@section('content')
<div class="flex min-h-screen flex-col bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-6 py-12 pb-24">
    <div class="flex-1">
        <div class="mb-12 text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-[32px] bg-white shadow-xl shadow-pink-100 ring-1 ring-pink-50">
                <span class="text-5xl">🐣</span>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-slate-800">
                Easter<span class="text-pink-500">Eggs</span><span class="text-blue-400">.</span>
            </h1>
            <p class="mt-3 text-sm font-medium text-slate-400">
                Junte-se à nós e comece a coletar seus lucros.
            </p>
        </div>

        <div class="rounded-[40px] bg-white p-8 shadow-2xl shadow-pink-200/50 border border-pink-50">
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
                @csrf

                @if (session('message'))
                    <div class="rounded-2xl bg-red-50 p-4 text-xs font-bold text-red-500 border border-red-100">
                        {{ is_string(session('message')) ? session('message') : json_encode(session('message')) }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="phone" class="ml-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Telefone</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-4 text-pink-400">phone_iphone</span>
                        <input type="tel" id="phone" name="phone" required
                            class="w-full rounded-2xl border-none bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none ring-1 ring-slate-100 transition-all focus:bg-white focus:ring-2 focus:ring-pink-200"
                            placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="password" class="ml-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Senha</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">lock_open</span>
                            <input type="password" id="password" name="password" required
                                class="w-full rounded-2xl border-none bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none ring-1 ring-slate-100 transition-all focus:bg-white focus:ring-2 focus:ring-pink-200"
                                placeholder="********">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="confirm-password" class="ml-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Confirmar</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">lock</span>
                            <input type="password" id="confirm-password" name="confirm-password" required
                                class="w-full rounded-2xl border-none bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none ring-1 ring-slate-100 transition-all focus:bg-white focus:ring-2 focus:ring-pink-200"
                                placeholder="********">
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="invite-code" class="ml-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Código de Convite</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-4 text-pink-400">loyalty</span>
                        <input type="text" id="invite-code" name="ref_by" value="{{ request()->get('inviteCode') }}"
                            class="w-full rounded-2xl border-none bg-slate-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none ring-1 ring-slate-100 transition-all focus:bg-white focus:ring-2 focus:ring-pink-200 uppercase tracking-widest"
                            placeholder="CÓDIGO OPCIONAL">
                    </div>
                </div>

                <div class="flex items-start gap-3 px-1 pt-2">
                    <input type="checkbox" id="accept-terms" name="accept-terms" required
                        class="h-5 w-5 rounded-lg border-slate-200 text-pink-500 focus:ring-pink-200 cursor-pointer">
                    <label for="accept-terms" class="text-[11px] font-bold text-slate-400 leading-tight">
                        Eu aceito os <a href="#" class="text-pink-500 underline underline-offset-4">Termos e Condições</a> e a Política de Privacidade.
                    </label>
                </div>

                <button type="submit"
                    class="flex w-full items-center justify-center gap-3 rounded-[24px] bg-slate-800 py-5 text-sm font-bold text-white shadow-xl transition-all hover:bg-pink-500 active:scale-[0.98]">
                    <span>CRIAR MINHA CONTA</span>
                    <span class="material-symbols-outlined">how_to_reg</span>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-12 text-center">
        <p class="text-sm font-medium text-slate-400">
            Já possui uma conta ativa?
            <a href="{{ route('login') }}" class="font-bold text-pink-500 hover:text-pink-600 underline-offset-4 hover:underline transition-all">
                Faça Login
            </a>
        </p>
    </div>
</div>
@endsection
