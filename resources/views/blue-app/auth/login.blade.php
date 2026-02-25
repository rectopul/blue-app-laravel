@extends('layouts.blueapp')

@section('content')
    <div x-data="loginApp()" class="min-h-screen flex flex-col justify-center bg-gradient-to-b from-[#CFE7FF] to-[#EEF4F9] px-6 py-12">
        {{-- Logo & Welcome --}}
        <div class="mb-12 text-center">
            <div class="mx-auto w-24 h-24 bg-white rounded-[32px] shadow-xl shadow-blue-200/50 flex items-center justify-center mb-6 animate-bounce">
                <span class="text-4xl">🚀</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Invest<span class="text-[#2C95EF]">Loop</span></h1>
            <p class="mt-2 text-sm text-slate-500 font-medium uppercase tracking-widest">Bem-vindo de volta!</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-[40px] p-8 shadow-2xl shadow-slate-200 border border-white">
            <form action="{{ route('login.submit') }}" method="POST" @submit="handleSubmit($event)">
                @csrf

                @if (session('error'))
                    <div class="mb-6 flex items-center gap-3 bg-red-50 text-red-600 p-4 rounded-2xl border border-red-100 text-xs font-bold uppercase tracking-tight">
                        <span class="material-symbols-outlined !text-lg">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-6">
                    {{-- Phone Field --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Telefone</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-[#2C95EF] transition-colors">
                                <span class="material-symbols-outlined !text-xl">phone_iphone</span>
                            </span>
                            <input type="tel" name="auth" x-model="phone" @input="applyPhoneMask"
                                class="block w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-2 focus:ring-[#2C95EF] transition-all"
                                placeholder="(00) 00000-0000" required autocomplete="off" />
                        </div>
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <div class="flex justify-between items-center mb-2 px-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Senha</label>
                            <a href="#" class="text-[10px] font-bold text-[#2C95EF] uppercase tracking-widest">Esqueceu?</a>
                        </div>
                        <div class="relative group" x-data="{ show: false }">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-[#2C95EF] transition-colors">
                                <span class="material-symbols-outlined !text-xl">lock</span>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="password"
                                class="block w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-12 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-2 focus:ring-[#2C95EF] transition-all"
                                placeholder="••••••••" required />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-300 hover:text-slate-500">
                                <span class="material-symbols-outlined !text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center">
                    <input type="checkbox" id="remember" class="w-5 h-5 rounded-lg border-slate-200 text-[#2C95EF] focus:ring-[#2C95EF] transition-all cursor-pointer">
                    <label for="remember" class="ml-3 text-xs font-bold text-slate-500 cursor-pointer select-none">Mantenha-me conectado</label>
                </div>

                <button type="submit"
                    class="mt-10 w-full bg-slate-900 text-white font-black py-5 rounded-3xl shadow-xl shadow-slate-200 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    ACESSAR CONTA
                    <span class="material-symbols-outlined !text-lg">arrow_forward</span>
                </button>

                <div class="mt-10 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        Ainda não tem conta?
                        <a href="{{ route('register') }}" class="ml-1 text-[#2C95EF] border-b-2 border-[#2C95EF]/20 hover:border-[#2C95EF] transition-all pb-0.5">
                            Abra agora
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-12 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} InvestLoop. Todos os direitos reservados.</p>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush

    @push('scripts')
    <script>
        function loginApp() {
            return {
                phone: '',
                applyPhoneMask(e) {
                    let val = this.phone.replace(/\D/g, "");
                    if (val.length > 11) val = val.substring(0, 11);

                    let masked = "";
                    if (val.length > 0) {
                        masked = "(" + val.substring(0, 2);
                        if (val.length > 2) {
                            masked += ") " + val.substring(2, 7);
                            if (val.length > 7) {
                                masked += "-" + val.substring(7, 11);
                            }
                        }
                    }
                    this.phone = masked;
                },
                handleSubmit(e) {
                    // Remove mask before submit if necessary, but the controller handles it
                }
            }
        }
    </script>
    @endpush
@endsection
