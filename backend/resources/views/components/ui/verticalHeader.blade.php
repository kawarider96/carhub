<aside class="w-64 h-screen bg-panel border-r border-border p-6 space-y-8">

    <!-- LOGO -->
    <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-accent rounded-full"></div>
        <span class="text-accent font-bold tracking-widest">SARS.SYSTEM</span>
    </div>

    <!-- NAVIGATION -->
    <nav class="space-y-2 text-sm">

        @if(auth()->user()->role === 'user')
            {{-- USER: Dashboard --}}
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
                Vezérlőpult
            </a>

            {{-- USER: Profil --}}
            <a href="{{ route('profile.show') }}"
               class="group flex items-center px-3 py-2 rounded-lg relative
                    {{ request()->routeIs('profile.*') 
                        ? 'bg-white/5 text-accent font-semibold'
                        : 'text-gray-400 hover:text-accent transition' }}">
                @if (request()->routeIs('profile.*'))
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent"></span>
                @else
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent
                        scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                @endif
                Profil
            </a>

            {{-- USER: Kedvenc autók --}}
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

        @elseif(auth()->user()->role === 'admin')

            {{-- ADMIN: Dashboard --}}
            <a href="{{ route('admin.dashboard.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg relative
                    {{ request()->routeIs('admin.dashboard.index') 
                        ? 'bg-white/5 text-accent font-semibold'
                        : 'text-gray-400 hover:text-accent transition' }}">
                @if (request()->routeIs('admin.dashboard.index'))
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent"></span>
                @else
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent
                        scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                @endif
                Admin Vezérlőpult
            </a>

            {{-- ADMIN: Kérelmek --}}
            <a href="{{ route('admin.requests.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg relative
                    {{ request()->routeIs('admin.requests.*') 
                        ? 'bg-white/5 text-accent font-semibold'
                        : 'text-gray-400 hover:text-accent transition' }}">
                @if (request()->routeIs('admin.requests.*'))
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent"></span>
                @else
                    <span class="absolute left-0 top-0 h-full w-1 bg-accent
                        scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
                @endif
                Kérelmek
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
