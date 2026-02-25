@extends('layouts.blueapp')

@section('content')
    <div x-data="registerApp()" class="min-h-screen flex flex-col justify-center bg-gradient-to-b from-[#CFE7FF] to-[#EEF4F9] px-6 py-12">
        {{-- Header --}}
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Criar <span class="text-[#2C95EF]">Conta</span></h1>
            <p class="mt-2 text-sm text-slate-500 font-medium uppercase tracking-widest">Junte-se à revolução InvestLoop</p>
        </div>

        {{-- Register Card --}}
        <div class="bg-white rounded-[40px] p-8 shadow-2xl shadow-slate-200 border border-white">
            <form action="{{ route('register.submit') }}" method="POST" @submit="handleSubmit($event)">
                @csrf

                @if (session('message'))
                    <div class="mb-6 flex items-center gap-3 bg-blue-50 text-[#2C95EF] p-4 rounded-2xl border border-blue-100 text-xs font-bold uppercase tracking-tight">
                        <span class="material-symbols-outlined !text-lg">info</span>
                        {{ is_array(session('message')) ? implode(', ', session('message')) : session('message') }}
                    </div>
                @endif

                <div class="space-y-5">
                    {{-- Phone Field --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Telefone</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-[#2C95EF] transition-colors">
                                <span class="material-symbols-outlined !text-xl">phone_iphone</span>
                            </span>
                            <input type="tel" name="phone" x-model="form.phone" @input="applyPhoneMask"
                                class="block w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-2 focus:ring-[#2C95EF] transition-all"
                                placeholder="(00) 00000-0000" required />
                        </div>
                    </div>

                    {{-- Password Fields --}}
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Senha de Acesso</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-[#2C95EF] transition-colors">
                                    <span class="material-symbols-outlined !text-xl">lock</span>
                                </span>
                                <input type="password" name="password" x-model="form.password"
                                    class="block w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-2 focus:ring-[#2C95EF] transition-all"
                                    placeholder="Mínimo 6 caracteres" required />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Confirmar Senha</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-[#2C95EF] transition-colors">
                                    <span class="material-symbols-outlined !text-xl">lock_reset</span>
                                </span>
                                <input type="password" name="confirm-password" x-model="form.confirmPassword"
                                    class="block w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-2 focus:ring-[#2C95EF] transition-all"
                                    placeholder="Repita sua senha" required />
                            </div>
                            <p x-show="form.password && form.confirmPassword && form.password !== form.confirmPassword"
                                class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">As senhas não coincidem</p>
                        </div>
                    </div>

                    {{-- Invite Code --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Código de Convite</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-[#2C95EF] transition-colors">
                                <span class="material-symbols-outlined !text-xl">confirmation_number</span>
                            </span>
                            <input type="text" name="ref_by" value="{{ request()->get('inviteCode') }}"
                                class="block w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-bold text-[#2C95EF] placeholder:text-slate-300 focus:ring-2 focus:ring-[#2C95EF] transition-all uppercase tracking-widest"
                                placeholder="CÓDIGO OPCIONAL" />
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" id="terms" required class="w-5 h-5 rounded-lg border-slate-200 text-[#2C95EF] focus:ring-[#2C95EF] transition-all cursor-pointer">
                    </div>
                    <label for="terms" class="ml-3 text-[10px] font-bold text-slate-500 uppercase tracking-tight leading-relaxed cursor-pointer select-none">
                        Eu aceito os <a href="#" class="text-[#2C95EF] underline decoration-[#2C95EF]/30">termos de uso</a> e a política de privacidade.
                    </label>
                </div>

                <button type="submit" :disabled="form.password !== form.confirmPassword"
                    class="mt-10 w-full bg-[#2C95EF] text-white font-black py-5 rounded-3xl shadow-xl shadow-blue-200 active:scale-[0.98] transition-all flex items-center justify-center gap-3 disabled:opacity-50">
                    CRIAR MINHA CONTA
                    <span class="material-symbols-outlined !text-lg">person_add</span>
                </button>

                <div class="mt-10 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        Já possui conta?
                        <a href="{{ route('login') }}" class="ml-1 text-slate-900 border-b-2 border-slate-200 hover:border-slate-900 transition-all pb-0.5">
                            Fazer Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush

    @push('scripts')
    <script>
        function registerApp() {
            return {
                form: {
                    phone: '',
                    password: '',
                    confirmPassword: ''
                },
                applyPhoneMask() {
                    let val = this.form.phone.replace(/\D/g, "");
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
                    this.form.phone = masked;
                },
                handleSubmit(e) {
                    if (this.form.password !== this.form.confirmPassword) {
                        e.preventDefault();
                        alert('As senhas não coincidem!');
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
