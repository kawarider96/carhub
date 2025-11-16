<!-- ADMIN SIDEBAR -->
<aside class="w-64 h-screen bg-panel border-r border-border p-6 space-y-8">

    <!-- LOGO -->
    <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-accent rounded-full"></div>
        <span class="text-accent font-bold tracking-widest">SARS.ADMIN</span>
    </div>

    <!-- NAVIGATION -->
    <nav class="space-y-2 text-sm">

        {{-- Kezdőlap --}}
        <a href="{{ route('dashboard') }}"
           class="group flex items-center px-3 py-2 rounded-lg
                relative
                {{ request()->routeIs('dashboard') 
                    ? 'bg-white/5 text-accent font-semibold'
                    : 'text-gray-400 hover:text-accent transition' }}">
            @if (request()->routeIs('dashboard'))
                <span class="absolute left-0 top-0 h-full w-1 bg-accent"></span>
            @else
                <span class="absolute left-0 top-0 h-full w-1 bg-accent
                              scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
            @endif
            Kezdőlap
        </a>

        {{-- Kedvenc autók --}}
        <a href="#"
           class="group flex items-center px-3 py-2 rounded-lg
                text-gray-400 hover:text-accent transition relative">
            <span class="absolute left-0 top-0 h-full w-1 bg-accent
                          scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
            Kedvenc autók
        </a>

        {{-- Felhasználók --}}
        <a href="#"
           class="group flex items-center px-3 py-2 rounded-lg
                text-gray-400 hover:text-accent transition relative">
            <span class="absolute left-0 top-0 h-full w-1 bg-accent
                          scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
            Felhasználók
        </a>

        {{-- Autók --}}
        <a href="#"
           class="group flex items-center px-3 py-2 rounded-lg
                text-gray-400 hover:text-accent transition relative">
            <span class="absolute left-0 top-0 h-full w-1 bg-accent
                          scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
            Autók
        </a>

        {{-- LOGOUT --}}
        <a href="{{ route('logout') }}"
           class="group flex items-center px-3 py-2 rounded-lg
                text-gray-400 hover:text-red-500 transition relative">
            <span class="absolute left-0 top-0 h-full w-1 bg-red-500
                          scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></span>
            Kijelentkezés
        </a>

    </nav>

</aside>
