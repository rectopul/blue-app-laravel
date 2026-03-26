@extends('layouts.blueapp')

@section('content')
    <div class="pb-32">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-b-[40px] bg-gradient-to-b from-[#FFF5F8] via-[#FFEBF2] to-[#F0F7FF] px-5 pt-8 pb-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="grid h-12 w-12 place-items-center rounded-[20px] bg-white shadow-sm border border-pink-50 active:scale-90 transition-all">
                    <span class="material-symbols-outlined text-pink-400">arrow_back</span>
                </a>
                <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Meu <span class="text-pink-500">Perfil</span></h1>
                <div class="w-12"></div>
            </div>

            <div class="mt-8 flex flex-col items-center">
                <div class="relative">
                    <div class="w-24 h-24 rounded-[35px] bg-white p-1 shadow-xl shadow-pink-100 border border-pink-50">
                        <div class="w-full h-full rounded-[30px] bg-gradient-to-br from-pink-100 to-blue-100 flex items-center justify-center">
                            <span class="text-4xl">🐰</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-2 -right-2 h-8 w-8 rounded-xl bg-emerald-500 border-4 border-white flex items-center justify-center text-[10px] text-white font-bold">✓</div>
                </div>
                <h2 class="mt-4 text-xl font-black text-slate-800">{{ user()->name }}</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ user()->email }}</p>
            </div>
        </div>

        {{-- Menu Grid --}}
        <div class="px-5 -mt-6">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('user.personal-details') }}" class="bg-white p-6 rounded-[35px] shadow-xl shadow-black/5 border border-pink-50 flex flex-col items-center text-center group active:scale-95 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <span class="text-2xl">👤</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Dados Pessoais</span>
                </a>

                <a href="{{ route('user.bank') }}" class="bg-white p-6 rounded-[35px] shadow-xl shadow-black/5 border border-blue-50 flex flex-col items-center text-center group active:scale-95 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <span class="text-2xl">🏦</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Minha Carteira</span>
                </a>

                <a href="{{ route('history') }}" class="bg-white p-6 rounded-[35px] shadow-xl shadow-black/5 border border-emerald-50 flex flex-col items-center text-center group active:scale-95 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <span class="text-2xl">🗓️</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Transações</span>
                </a>

                <a href="{{ route('setting') }}" class="bg-white p-6 rounded-[35px] shadow-xl shadow-black/5 border border-amber-50 flex flex-col items-center text-center group active:scale-95 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <span class="text-2xl">⚙️</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Configurações</span>
                </a>
            </div>

            <div class="mt-8 space-y-3">
                <a href="{{ route('user.invite') }}" class="w-full bg-slate-900 p-5 rounded-[30px] flex items-center justify-between text-white group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <span>🎁</span>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-black uppercase tracking-widest">Convidar Amigos</p>
                            <p class="text-[9px] text-white/50 font-bold">GANHE COMISSÕES EM 3 NÍVEIS</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-white/40">chevron_right</span>
                </a>

                <a href="{{ route('logout') }}" class="w-full bg-pink-50 p-5 rounded-[30px] flex items-center justify-between text-pink-500 group border border-pink-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center">
                            <span class="material-symbols-outlined">logout</span>
                        </div>
                        <p class="text-xs font-black uppercase tracking-widest">Encerrar Sessão</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
