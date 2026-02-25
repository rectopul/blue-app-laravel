@extends('layouts.blueapp')

@section('content')
    <div class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#2C95EF] to-[#0E7FE7] px-6 pt-12 pb-20 text-white">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>

            <div class="flex items-center gap-5 relative z-10">
                <div class="relative">
                    <div class="w-20 h-20 rounded-[28px] bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-xl">
                        <span class="material-symbols-outlined text-4xl">person</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-[#0E7FE7] rounded-full shadow-sm"></div>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight">{{ $user->name }}</h1>
                    <p class="text-white/60 text-xs font-bold uppercase tracking-widest flex items-center gap-1 mt-0.5">
                        <span class="material-symbols-outlined !text-xs text-emerald-400">verified</span>
                        Conta Verificada
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-12 relative z-20 space-y-6">
            {{-- Balance Card --}}
            <div class="bg-white rounded-[32px] p-8 shadow-xl shadow-slate-200/50 border border-slate-50">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Saldo Total Disponível</span>
                        <h2 class="text-4xl font-black text-slate-900 tracking-tighter">R$ {{ number_format($user->balance, 2, ',', '.') }}</h2>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-2xl text-[#2C95EF]">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('user.deposit') }}" class="flex-1 bg-slate-900 text-white text-center py-4 rounded-2xl text-xs font-black transition-all active:scale-95 shadow-lg shadow-slate-200 uppercase tracking-widest">
                        Depositar
                    </a>
                    <a href="{{ route('user.withdraw') }}" class="flex-1 bg-[#EAF4FF] text-[#2C95EF] text-center py-4 rounded-2xl text-xs font-black transition-all active:scale-95 uppercase tracking-widest">
                        Sacar
                    </a>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-[28px] shadow-sm border border-slate-50">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined !text-lg">trending_up</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Rendimentos Hoje</p>
                    <p class="text-sm font-black text-slate-900 mt-1">R$ {{ number_format($todayEarnings, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-5 rounded-[28px] shadow-sm border border-slate-50">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined !text-lg">groups</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Minha Equipe</p>
                    <p class="text-sm font-black text-slate-900 mt-1">{{ $team_size }} membros</p>
                </div>
                <div class="bg-white p-5 rounded-[28px] shadow-sm border border-slate-50">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined !text-lg">add_circle</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Depositado</p>
                    <p class="text-sm font-black text-slate-900 mt-1">R$ {{ number_format($totalDeposited, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-5 rounded-[28px] shadow-sm border border-slate-50">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined !text-lg">do_not_disturb_on</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Sacado</p>
                    <p class="text-sm font-black text-slate-900 mt-1">R$ {{ number_format($totalWithdrawn, 2, ',', '.') }}</p>
                </div>
            </div>

            {{-- Menu List --}}
            <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-50 divide-y divide-slate-50">
                <a href="{{ route('transactions.history') }}" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-colors">
                            <span class="material-symbols-outlined">receipt_long</span>
                        </div>
                        <span class="text-slate-700 font-bold text-sm">Histórico de Transações</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>

                <a href="{{ route('user.team') }}" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors">
                            <span class="material-symbols-outlined">share</span>
                        </div>
                        <span class="text-slate-700 font-bold text-sm">Indicar Amigos</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>

                <a href="{{ route('setting') }}" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-amber-50 group-hover:text-amber-500 transition-colors">
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                        <span class="text-slate-700 font-bold text-sm">Configurações de Segurança</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>

                <a href="{{ url('logout') }}" class="flex items-center justify-between p-5 hover:bg-red-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-red-50/50 flex items-center justify-center text-red-400 group-hover:bg-red-100 group-hover:text-red-600 transition-colors">
                            <span class="material-symbols-outlined">logout</span>
                        </div>
                        <span class="text-red-500 font-bold text-sm">Sair da Conta</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>
            </div>
        </div>

        {{-- Bottom Navigation --}}
        <div class="fixed bottom-0 left-0 right-0 mx-auto max-w-[420px] bg-white/80 backdrop-blur-md px-6 pb-8 pt-4 border-t border-slate-100 z-40">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-black/5 text-slate-400 hover:text-[#2C95EF] transition-colors">
                    <span class="material-symbols-outlined">home</span>
                </a>

                <a href="{{ route('user.deposit') }}" class="flex h-14 flex-1 items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 text-sm font-bold text-white shadow-xl active:scale-[0.98] transition-all">
                    <span class="text-xl">＋</span>
                    Novo Depósito
                </a>

                <a href="{{ route('profile') }}" class="grid h-12 w-12 place-items-center rounded-2xl bg-[#2C95EF] text-white shadow-lg shadow-blue-200">
                    <span class="material-symbols-outlined">person</span>
                </a>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @endpush
@endsection
