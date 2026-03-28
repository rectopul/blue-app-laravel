@extends('layouts.blueapp')

@section('content')
    <div class="pb-32">
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('profile') }}" class="grid h-12 w-12 place-items-center rounded-[20px] border border-pink-50 bg-white shadow-sm transition-all active:scale-90">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold tracking-tight text-slate-800">Senha <span class="text-pink-500">Financeira</span></h1>
                <div class="w-12"></div>
            </div>

            <div class="mt-8 text-center">
                <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.2em] text-pink-400">Segurança de Saques</p>
                <h2 class="text-xl font-black text-slate-800">Alterar Senha de Saque</h2>
            </div>
        </div>

        <div class="-mt-6 px-5">
            <div class="rounded-[40px] border border-pink-50 bg-white p-7 shadow-xl shadow-black/5">
                <form action="{{ route('user.change.tpassword.confirmation') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Senha Financeira Atual</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">lock_open</span>
                            <input :type="show ? 'text' : 'password'" name="old_password" required
                                class="w-full rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-12 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-4 text-slate-400 active:scale-90 transition-all">
                                <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Nova Senha Financeira</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">lock</span>
                            <input :type="show ? 'text' : 'password'" name="new_password" required
                                class="w-full rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-12 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-4 text-slate-400 active:scale-90 transition-all">
                                <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="ml-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Confirmar Nova Senha</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-pink-400">verified</span>
                            <input :type="show ? 'text' : 'password'" name="confirm_password" required
                                class="w-full rounded-[24px] border border-slate-100 bg-slate-50 py-4 pl-12 pr-12 text-sm font-bold text-slate-700 outline-none transition-all focus:border-pink-200 focus:bg-white"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-4 text-slate-400 active:scale-90 transition-all">
                                <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-4 flex w-full items-center justify-center gap-3 rounded-[28px] bg-slate-800 px-6 py-5 font-bold text-white shadow-xl transition-all hover:bg-pink-500 active:scale-[0.98]">
                        <span class="material-symbols-outlined">save</span>
                        <span>Confirmar Alteração</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @include('alert-message')
@endsection
