<header class="bg-panel border-b border-border shadow-soft">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-90 transition">
            <div class="w-3 h-3 bg-accent rounded-full"></div>
            <h1 class="text-2xl font-bold tracking-widest">
                <span class="text-accent">SARS</span><span class="text-textlight">.SYSTEM</span>
            </h1>
        </a>

        <!-- NAVIGATION -->
        <nav class="flex space-x-6 text-sm font-medium">

            {{-- KEZDŐLAP --}}
            <a href="{{ route('home') }}"
               class="group relative px-2 py-1 {{ request()->routeIs('home') ? 'text-accent font-semibold' : 'text-gray-300 hover:text-accent' }}">

                {{-- animated bar --}}
                <span class="absolute left-0 top-0 h-full w-1 bg-accent 
                    {{ request()->routeIs('home') ? '' : 'scale-y-0 group-hover:scale-y-100 transition-transform origin-top'}}">
                </span>

                Kezdőlap
            </a>

            @guest
                {{-- BEJELENTKEZÉS --}}
                <a href="{{ route('user.login') }}"
                   class="group relative px-2 py-1 {{ request()->routeIs('user.login') ? 'text-accent font-semibold' : 'text-gray-300 hover:text-accent' }}">

                    <span class="absolute left-0 top-0 h-full w-1 bg-accent
                        {{ request()->routeIs('user.login') ? '' : 'scale-y-0 group-hover:scale-y-100 transition-transform origin-top'}}">
                    </span>

                    Bejelentkezés
                </a>

                {{-- REGISZTRÁCIÓ --}}
                <a href="{{ route('user.register') }}"
                   class="group relative px-2 py-1 {{ request()->routeIs('user.register') ? 'text-accent font-semibold' : 'text-gray-300 hover:text-accent' }}">

                    <span class="absolute left-0 top-0 h-full w-1 bg-accent
                        {{ request()->routeIs('user.register') ? '' : 'scale-y-0 group-hover:scale-y-100 transition-transform origin-top'}}">
                    </span>

                    Regisztráció
                </a>
            @endguest

            @auth
                {{-- KIJELENTKEZÉS --}}
                <form method="POST" action="{{ route('logout') }}" class="relative group">
                    @csrf
                    <button class="px-2 py-1 text-gray-300 hover:text-red-500">
                        <span class="absolute left-0 top-0 h-full w-1 bg-red-500 scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                        Kijelentkezés
                    </button>
                </form>
            @endauth

        </nav>

    </div>
</header>
