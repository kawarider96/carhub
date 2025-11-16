<aside class="w-64 h-screen bg-panel border-r border-border p-6 space-y-8">

    <!-- LOGO -->
    <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-accent rounded-full"></div>
        <span class="text-accent font-bold tracking-widest">SARS.SYSTEM</span>
    </div>

    <!-- NAVIGATION -->
    <nav class="space-y-2 text-sm">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard.index') }}"
           class="group flex items-center px-3 py-2 rounded-lg relative
                {{ request()->routeIs('dashboard.index') 
                    ? 'bg-white/5 text-accent font-semibold'
                    : 'text-gray-400 hover:text-accent transition' }}">
            @if (request()->routeIs('dashboard.index'))
                <span class="absolute left-0 top-0 h-full w-1 bg-accent"></span>
            @else
                <span class="absolute left-0 top-0 h-full w-1 bg-accent
                    scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
            @endif
            Dashboard
        </a>

        {{-- Kedvenc autók – csak user --}}
        @if(auth()->user()->role === 'user')
            <a href="{{ route('favorites.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg relative
                    {{ request()->routeIs('favorites.*') 
                        ? 'bg-white/5 text-accent font-semibold'
                        : 'text-gray-400 hover:text-accent transition' }}">
                @if (request()->routeIs('favorites.*'))
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent"></span>
                @else
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent
                        scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                @endif
                Kedvenc autók
            </a>
        @endif

        {{-- Admin menu --}}
        @if(auth()->user()->role === 'admin')

            <a href="#"
               class="group flex items-center px-3 py-2 rounded-lg relative
                      text-gray-400 hover:text-accent transition">
                <span class="absolute left-0 top-0 h-full w-1 bg-accent
                            scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                Felhasználók
            </a>

            <a href="#"
               class="group flex items-center px-3 py-2 rounded-lg relative
                      text-gray-400 hover:text-accent transition">
                <span class="absolute left-0 top-0 h-full w-1 bg-accent
                            scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                Autók
            </a>

        @endif

        {{-- Logout --}}
        <form method="POST" action="{{ route('auth.logout') }}" class="group flex">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded-lg text-gray-400 hover:text-red-500 transition relative">
                <span class="absolute left-0 top-0 h-full w-1 bg-red-500
                              scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                Kijelentkezés
            </button>
        </form>

    </nav>

</aside>
