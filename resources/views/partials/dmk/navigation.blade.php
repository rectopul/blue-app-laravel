<div class="w-full grid grid-cols-4 gap-4 py-6 px-2 bg-white rounded-[28px] border border-slate-100 shadow-sm mt-4">
    <div class="flex flex-col items-center group">
        <a href="https://t.me/+Qm95K93C1xhmNzZh" target="_blank"
            class="flex h-14 w-14 mb-2 justify-center items-center rounded-2xl bg-blue-50 text-blue-500 transition-all duration-300 group-active:scale-90 group-hover:bg-blue-500 group-hover:text-white shadow-sm">
            <i class="fab fa-telegram text-[24px]"></i>
        </a>
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Comunidade</span>
    </div>

    <div class="flex flex-col items-center group">
        <a href="https://t.me/+Qm95K93C1xhmNzZh" target="_blank"
            class="flex h-14 w-14 mb-2 justify-center items-center rounded-2xl bg-elm-50 text-elm-600 transition-all duration-300 group-active:scale-90 group-hover:bg-elm-600 group-hover:text-white shadow-sm">
            <span class="material-symbols-outlined !text-[24px]">
                rocket_launch
            </span>
        </a>
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Serviços</span>
    </div>

    <div class="flex flex-col items-center group">
        <button onclick="storeCheckin()"
            class="flex h-14 w-14 mb-2 justify-center items-center rounded-2xl bg-amber-50 text-amber-600 transition-all duration-300 group-active:scale-90 group-hover:bg-amber-600 group-hover:text-white shadow-sm">
            <span class="material-symbols-outlined !text-[24px]">
                calendar_check
            </span>
        </button>
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Check-in</span>
    </div>

    <div class="flex flex-col items-center group">
        <a href="{{ route('user.team') }}"
            class="flex h-14 w-14 mb-2 justify-center items-center rounded-2xl bg-purple-50 text-purple-600 transition-all duration-300 group-active:scale-90 group-hover:bg-purple-600 group-hover:text-white shadow-sm">
            <span class="material-symbols-outlined !text-[24px]">
                diversity_3
            </span>
        </a>
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Equipe</span>
    </div>
</div>

<script>
    // Mantendo sua lógica funcional de Checkin
    async function storeCheckin() {
        try {
            const options = {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    accept: "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }

            const req = await fetch("{{ route('checkins.store') }}", options)

            const response = await req.json()

            console.log("response to checkin", response)

            if (!req.ok) {
                console.log("Erro de checkin", response)
                return alert(response.message)
            }

            alert("Checkin realizado com sucesso!")

            return window.location.reload();
        } catch (error) {
            console.log("Erro de checkin", error)
        }
    }
</script>
