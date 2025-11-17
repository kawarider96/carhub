@extends('layouts.baseLayout')

@section('title', 'Admin Vezérlőpult')

@section('page')
<main class="p-10 space-y-10">

    <!-- WELCOME -->
    <h1 class="text-3xl font-bold tracking-wide">
        Admin <span class="text-accent">vezérlőpult</span>
    </h1>
    <p class="text-gray-400">Rendszer áttekintő panel</p>

    <!-- CARDS -->
    <div class="grid md:grid-cols-3 gap-6">

        <!-- Összes kedvenc autó -->
        <div class="bg-panel border border-border p-6 rounded-xl shadow-card">
            <p class="text-gray-400 text-sm">Összes kedvenc autó</p>
            <p class="text-4xl font-extrabold text-accent mt-2">
                {{ $favoriteCount }}
            </p>
        </div>

        <!-- Összes feltöltött kép -->
        <div class="bg-panel border border-border p-6 rounded-xl shadow-card">
            <p class="text-gray-400 text-sm">Összes feltöltött kép</p>
            <p class="text-4xl font-extrabold text-accent mt-2">
                {{ $imageCount }}
            </p>
        </div>

        <!-- Összes felhasználó -->
        <div class="bg-panel border border-border p-6 rounded-xl shadow-card">
            <p class="text-gray-400 text-sm">Összes felhasználó</p>
            <p class="text-4xl font-extrabold text-accent mt-2">
                {{ $userCount }}
            </p>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.brands.create') }}" class="bg-accent text-black px-6 py-3 rounded-xl font-semibold hover:bg-green-400 transition">
            Autómárka
        </a>
        <a href="{{ route('admin.users.create') }}" class="bg-panel border border-border px-6 py-3 rounded-xl hover:border-accent transition">
            Új felhasználó létrehozása
        </a>
    </div>

    <!-- TABLE: Utoljára regisztrált felhasználók -->
    <div class="bg-panel border border-border p-6 rounded-xl shadow-card">

        <h2 class="text-xl font-bold mb-4">Felhasználók</h2>

        @if ($users->count() > 0)
            <table class="w-full text-left text-gray-300 text-sm">

                <thead class="text-gray-500 border-b border-border">
                    <tr>
                        <th class="py-2">Név</th>
                        <th>Felhasználónév</th>
                        <th>Szerepkör</th>
                        <th>Létrehozva</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-border">
                    @foreach ($users as $u)
                        <tr>
                            <td class="py-3">{{ $u->full_name }}</td>
                            <td>{{ $u->username }}</td>
                            <td>{{ $u->role }}</td>
                            <td>{{ optional($u->created_at)->format('Y-m-d') }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="px-3 py-1 rounded border border-border hover:border-accent text-gray-300 hover:text-accent transition">Szerkesztés</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @else
            <p class="text-gray-500">Nincs felhasználó.</p>
        @endif

    </div>

</main>
@endsection
