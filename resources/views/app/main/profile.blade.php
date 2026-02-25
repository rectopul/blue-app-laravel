@extends('layouts.dmk')

@section('title', 'Meu Perfil')

@section('content')
    <div class="min-h-screen bg-mirage-950 pb-24">
        <header class="bg-mirage-900 pt-12 pb-20 px-6 rounded-b-[40px] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>

            <div class="flex items-center gap-5 relative z-10">
                <div class="relative">
                    <img src="{{ asset(main_root() . '/assets/img/logobeee.png') }}" alt="{{ env('APP_NAME') }}"
                        class="w-20 h-20 rounded-[24px] border-2 border-emerald-500/30 p-1 object-cover shadow-lg shadow-emerald-500/10">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-mirage-900 rounded-full">
                    </div>
                </div>
                <div>
                    <h1 class="text-white text-xl font-black tracking-tight">{{ formatPhone(user()->phone) }}</h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest flex items-center gap-1">
                        <span class="material-symbols-outlined !text-xs text-emerald-500">verified</span>
                        Membro Ativo
                    </p>
                </div>
            </div>
        </header>

        <div class="px-6 -mt-12 relative z-20">
            <div class="bg-white rounded-[32px] p-6 shadow-2xl shadow-slate-900/20">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter block mb-1">Saldo
                            Disponível</span>
                        <h2 class="text-4xl font-black text-slate-900 tracking-tighter">{{ price(user()->balance) }}</h2>
                    </div>
                    <div class="bg-slate-100 p-2 rounded-xl">
                        <span class="material-symbols-outlined text-slate-900">account_balance_wallet</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <a href="{{ route('user.deposit') }}"
                        class="flex-1 bg-slate-900 hover:bg-elm-600 text-white text-center py-4 rounded-2xl text-xs font-black transition-all active:scale-95 shadow-lg shadow-slate-200 uppercase tracking-widest">
                        Depositar
                    </a>
                    <a href="{{ route('user.withdraw') }}"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center py-4 rounded-2xl text-xs font-black transition-all active:scale-95 uppercase tracking-widest">
                        Sacar
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 mt-8 grid grid-cols-2 gap-4">
            <div class="bg-mirage-900/40 border border-white/5 p-4 rounded-2xl">
                <span class="material-symbols-outlined text-blue-400 mb-2">add_circle</span>
                <p class="text-[9px] font-black text-slate-500 uppercase">Total Depositado</p>
                <p class="text-sm font-black text-elm-800">
                    {{ price(\App\Models\Deposit::where('user_id', user()->id)->where('status', 'approved')->sum('amount')) }}
                </p>
            </div>
            <div class="bg-mirage-900/40 border border-white/5 p-4 rounded-2xl">
                <span class="material-symbols-outlined text-amber-400 mb-2">do_not_disturb_on</span>
                <p class="text-[9px] font-black text-slate-500 uppercase">Total Sacado</p>
                <p class="text-sm font-black text-elm-800">
                    {{ price(\App\Models\Withdrawal::where('user_id', user()->id)->where('status', 'approved')->sum('amount')) }}
                </p>
            </div>
            <div class="bg-mirage-900/40 border border-white/5 p-4 rounded-2xl">
                <span class="material-symbols-outlined text-emerald-400 mb-2">trending_up</span>
                <p class="text-[9px] font-black text-slate-500 uppercase">Rendimentos Hoje</p>
                <p class="text-sm font-black text-elm-800">
                    {{ price(\App\Models\UserLedger::where('user_id', user()->id)->where('reason', 'daily_income')->whereDate('created_at', now())->sum('amount')) }}
                </p>
            </div>
            <div class="bg-mirage-900/40 border border-white/5 p-4 rounded-2xl">
                <span class="material-symbols-outlined text-purple-400 mb-2">groups</span>
                <p class="text-[9px] font-black text-slate-500 uppercase">Tamanho Equipe</p>
                <p class="text-sm font-black text-elm-800">{{ $team_size }} membros</p>
            </div>
        </div>

        <div class="px-6 mt-8">
            <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest ml-1 mb-3">Configurações e Suporte
            </h3>
            <div class="bg-white rounded-[24px] overflow-hidden shadow-sm">
                <a href="{{ route('history') }}"
                    class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors border-b border-slate-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <span class="material-symbols-outlined !text-lg">history</span>
                        </div>
                        <span class="text-slate-700 font-bold text-sm">Meu Histórico</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </a>

                <button onclick="openService()"
                    class="w-full flex items-center justify-between p-4 hover:bg-slate-50 transition-colors border-b border-slate-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <span class="material-symbols-outlined !text-lg">headset_mic</span>
                        </div>
                        <span class="text-slate-700 font-bold text-sm">Suporte Online</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300">chevron_right</span>
                </button>

                <button onclick="logoutt()"
                    class="w-full flex items-center justify-between p-4 hover:bg-red-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                            <span class="material-symbols-outlined !text-lg">logout</span>
                        </div>
                        <span class="text-red-600 font-bold text-sm">Sair da Conta</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <div id="serviceModal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeService()"></div>
        <div id="modalContent"
            class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] p-8 translate-y-full transition-transform duration-500">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            <div class="text-center mb-8">
                <h3 class="text-xl font-black text-slate-900">Atendimento VIP</h3>
                <p class="text-slate-500 text-sm mt-1 font-medium">Horário: 10:00 às 17:00</p>
            </div>
            <a href="https://t.me/+Qm95K93C1xhmNzZh" target="_blank"
                class="flex items-center justify-between bg-slate-900 text-white p-5 rounded-2xl active:scale-95 transition-all">
                <div class="flex items-center gap-4">
                    <div class="bg-white/10 p-2 rounded-xl">
                        <i class="fab fa-telegram-plane text-2xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-black text-sm uppercase">Falar no Telegram</p>
                        <p class="text-[10px] text-slate-400">Resposta em poucos minutos</p>
                    </div>
                </div>
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
        </div>
    </div>

    @include('partials.dmk.footer_menu')

    <script>
        function openService() {
            const modal = document.getElementById('serviceModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => content.classList.remove('translate-y-full'), 10);
        }

        function closeService() {
            const content = document.getElementById('modalContent');
            content.classList.add('translate-y-full');
            setTimeout(() => document.getElementById('serviceModal').classList.add('hidden'), 500);
        }

        function logoutt() {
            // Você pode adicionar um loader aqui se desejar
            window.location.href = "{{ url('logout') }}";
        }
    </script>
@endsection
