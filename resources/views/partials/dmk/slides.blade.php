<div class="relative w-full px-4 pt-4">
    <div class="slidesHomePage w-full overflow-hidden rounded-[28px] shadow-2xl shadow-slate-200/80">
        @foreach ($vipSlides as $image)
            <div class="relative w-full h-[100px] outline-none">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent z-10 pointer-events-none">
                </div>

                <img src="{{ asset(main_root() . '/' . $image->photo) }}" alt="Banner VIP"
                    class="w-full h-full object-cover object-center transform transition-transform duration-700 hover:scale-105" />

                <div class="absolute top-4 right-4 z-20">
                    <span
                        class="bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                        Exclusivo
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center gap-1.5 mt-4" id="custom-dots">
    </div>
</div>

<style>
    /* Estilização para os pontos (dots) do Slick ficarem modernos */
    .slick-dots {
        bottom: 15px !important;
        display: flex !important;
        justify-content: center;
        list-style: none;
        padding: 0;
        width: 100%;
        margin-top: 10px;
    }

    .slick-dots li {
        margin: 0 4px;
    }

    .slick-dots li button {
        width: 8px;
        height: 8px;
        padding: 0;
        background: #47bab6;
        border-radius: 100px;
        border: none;
        text-indent: -9999px;
        transition: all 0.3s ease;
    }

    .slick-dots li.slick-active button {
        width: 24px;
        /* Dot esticado estilo iPhone */
        background: #0b2528;
    }
</style>
