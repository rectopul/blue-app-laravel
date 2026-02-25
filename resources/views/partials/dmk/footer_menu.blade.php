<div class="fixed z-50 bottom-6 left-1/2 -translate-x-1/2 w-[95%] max-w-[440px]">
    <div
        class="relative bg-white/80 backdrop-blur-xl border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.1)] rounded-[24px] h-[70px] px-2 flex items-center justify-around">

        <a href="{{ route('dashboard') }}"
            class="relative flex flex-col items-center justify-center w-16 h-full transition-all duration-300 group">
            <span
                class="material-symbols-outlined !text-[26px] transition-transform duration-300 group-active:scale-90 {{ request()->routeIs('dashboard') ? 'text-elm-600' : 'text-elm-400' }}">
                home
            </span>
            <span
                class="text-[10px] font-bold mt-1 uppercase tracking-tighter {{ request()->routeIs('dashboard') ? 'text-elm-600' : 'text-elm-400' }}">
                Home
            </span>
            @if (request()->routeIs('dashboard'))
                <div
                    class="absolute -bottom-1 w-1.5 h-1.5 bg-elm-600 rounded-full shadow-[0_0_8px_rgba(13,148,136,0.6)]">
                </div>
            @endif
        </a>

        <a href="{{ route('purchase.history') }}"
            class="relative flex flex-col items-center justify-center w-16 h-full transition-all duration-300 group">
            <span
                class="material-symbols-outlined !text-[26px] transition-transform duration-300 group-active:scale-90 {{ request()->routeIs('purchase.history') ? 'text-elm-600' : 'text-elm-400' }}">
                account_balance
            </span>
            <span
                class="text-[10px] font-bold mt-1 uppercase tracking-tighter {{ request()->routeIs('purchase.history') ? 'text-elm-600' : 'text-elm-400' }}">
                Investir
            </span>
            @if (request()->routeIs('purchase.history'))
                <div
                    class="absolute -bottom-1 w-1.5 h-1.5 bg-elm-600 rounded-full shadow-[0_0_8px_rgba(13,148,136,0.6)]">
                </div>
            @endif
        </a>

        <a href="{{ route('user.team') }}"
            class="relative flex flex-col items-center justify-center w-16 h-full transition-all duration-300 group">
            <span
                class="material-symbols-outlined !text-[26px] transition-transform duration-300 group-active:scale-90 {{ request()->routeIs('user.team') ? 'text-elm-600' : 'text-elm-400' }}">
                diversity_3
            </span>
            <span
                class="text-[10px] font-bold mt-1 uppercase tracking-tighter {{ request()->routeIs('user.team') ? 'text-elm-600' : 'text-elm-400' }}">
                Equipe
            </span>
            @if (request()->routeIs('user.team'))
                <div
                    class="absolute -bottom-1 w-1.5 h-1.5 bg-elm-600 rounded-full shadow-[0_0_8px_rgba(13,148,136,0.6)]">
                </div>
            @endif
        </a>

        <a href="{{ route('profile') }}"
            class="relative flex flex-col items-center justify-center w-16 h-full transition-all duration-300 group">
            <div
                class="p-1 rounded-xl transition-all duration-300 {{ request()->routeIs('profile') ? 'bg-elm-50' : '' }}">
                <span
                    class="material-symbols-outlined !text-[26px] transition-transform duration-300 group-active:scale-90 {{ request()->routeIs('profile') ? 'text-elm-600' : 'text-elm-400' }}">
                    person_2
                </span>
            </div>
            <span
                class="text-[10px] font-bold mt-1 uppercase tracking-tighter {{ request()->routeIs('profile') ? 'text-elm-600' : 'text-elm-400' }}">
                Perfil
            </span>
            @if (request()->routeIs('profile'))
                <div
                    class="absolute -bottom-1 w-1.5 h-1.5 bg-elm-600 rounded-full shadow-[0_0_8px_rgba(13,148,136,0.6)]">
                </div>
            @endif
        </a>

    </div>
</div>
