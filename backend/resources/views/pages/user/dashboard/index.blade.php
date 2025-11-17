@extends('layouts.baseLayout')

@section('title', 'Vezérlőpult')

@section('page')

<main class="p-10 space-y-10">

    <!-- WELCOME -->
    <h1 class="text-3xl font-bold tracking-wide">
        Üdvözöljük, <span class="text-accent">{{ auth()->user()->full_name }}</span>
    </h1>
    <p class="text-gray-400">Felhasználói áttekintő panel</p>

    <!-- CARDS -->
    <div class="grid md:grid-cols-3 gap-6">

        <!-- Kedvenc autók -->
        <div class="bg-panel border border-border p-6 rounded-xl shadow-card">
            <p class="text-gray-400 text-sm">Kedvenc autók</p>
            <p class="text-4xl font-extrabold text-accent mt-2">
                {{ $favoriteCount }}
            </p>
        </div>

        <!-- Feltöltött képek -->
        <div class="bg-panel border border-border p-6 rounded-xl shadow-card">
            <p class="text-gray-400 text-sm">Feltöltött képek</p>
            <p class="text-4xl font-extrabold text-accent mt-2">
                {{ $imageCount }}
            </p>
        </div>

        <!-- Utolsó módosítás -->
        <div class="bg-panel border border-border p-6 rounded-xl shadow-card">
            <p class="text-gray-400 text-sm">Utolsó módosítás</p>

            @if ($lastModified)
                <p class="text-lg text-accent mt-2">
                    {{ $lastModified->carModel->name }}
                    — {{ $lastModified->updated_at->format('Y.m.d') }}
                </p>
            @else
                <p class="text-lg text-gray-500 mt-2">Nincs adat</p>
            @endif
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('favorites.create') }}" class="bg-accent text-black px-6 py-3 rounded-xl font-semibold hover:bg-green-400 transition">
            + Új kedvenc autó
        </a>
        <a class="bg-panel border border-border px-6 py-3 rounded-xl hover:border-accent transition">
            Hiányzó márka bejelentése
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-panel border border-border p-6 rounded-xl shadow-card">

        <h2 class="text-xl font-bold mb-4">Legutóbb módosított autók</h2>

        @if ($recentCars->count() > 0)
            <table class="w-full text-left text-gray-300 text-sm">

                <thead class="text-gray-500 border-b border-border">
                    <tr>
                        <th class="py-2">Típus</th>
                        <th>Évjárat</th>
                        <th>Szín</th>
                        <th>Módosítva</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-border">
                    @foreach ($recentCars as $car)
                        <tr>
                            <td class="py-3">{{ $car->carModel->name }}</td>
                            <td>{{ $car->year }}</td>
                            <td>{{ $car->color }}</td>
                            <td>{{ $car->updated_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        @else
            <p class="text-gray-500">Nincs módosított autó.</p>
        @endif

    </div>

</main>

@endsection
