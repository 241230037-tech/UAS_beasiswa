<nav class="bg-background border-b border-border sticky top-0 z-50 shadow-lg">
    <div class="max-w-screen-xl mx-auto px-4 flex items-center h-14 gap-3">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="flex items-center gap-1 mr-2 shrink-0 hover:opacity-90 transition-opacity">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6 text-red-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 14L3 9l9-5 9 5-9 5zm0 0v6m-6-3l6 3 6-3"/>

            </svg>

            <span class="text-lg font-black text-foreground tracking-tight">
                PORTAL<span class="text-red-600">BEASISWA</span>
            </span>

        </a>

        {{-- Search --}}
        <form class="flex-1 flex">

            <div class="relative w-full max-w-md">

                <input
                    type="text"
                    placeholder="Cari beasiswa..."
                    class="w-full bg-muted text-foreground px-4 py-2 pr-10 rounded-full text-sm border-0 focus:outline-none focus:ring focus:ring-red-500">

                <button
                    class="absolute right-3 top-1/2 -translate-y-1/2">

                    🔍

                </button>

            </div>

        </form>

        {{-- Right Menu --}}
        <div class="flex items-center gap-2">

            <button id="theme-toggle"
                class="p-2 hover:text-red-600">

                🌙

            </button>

            <a href="{{ route('login') }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-full text-white text-sm">

                Login

            </a>

            <button id="menu-button"
                    class="p-2">

                ☰

            </button>

        </div>

    </div>
</nav>