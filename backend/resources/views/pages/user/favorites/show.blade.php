@extends('layouts.baseLayout')

@section('title', 'Kedvenc autó')

@section('page')

<div class="max-w-4xl mx-auto space-y-10">

    <h1 class="text-3xl font-bold tracking-wide text-center">
        Kedvenc autó <span class="text-accent">részletei</span>
    </h1>

    {{-- KÉPGALÉRIA --}}
    <div class="bg-panel border border-border rounded-xl p-6 shadow-card">
        @php
            $imageUrls = $favoriteCar->images->isNotEmpty()
                ? $favoriteCar->images->map(fn($img) => route('favorites.images.show', ['favoriteCar' => $favoriteCar->id, 'image' => $img->id]))
                : collect([asset('images/nocar.png')]);
        @endphp

        <x-favorites.index.carousel :id="$favoriteCar->id" :images="$imageUrls" />
    </div>

    {{-- ADATOK --}}
    <div class="bg-panel border border-border rounded-xl p-6 shadow-card space-y-2">
        <div><span class="text-gray-400">Márka:</span> {{ $favoriteCar->carModel->brand->name }}</div>
        <div><span class="text-gray-400">Modell:</span> {{ $favoriteCar->carModel->name }}</div>
        <div><span class="text-gray-400">Évjárat:</span> {{ $favoriteCar->year ?? '-' }}</div>
        <div><span class="text-gray-400">Szín:</span> {{ $favoriteCar->color ?? '-' }}</div>
        <div><span class="text-gray-400">Üzemanyag:</span> {{ $favoriteCar->fuel ?? '-' }}</div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('favorites.index') }}" class="px-4 py-2 rounded-lg bg-white/5 border border-border hover:border-accent">Vissza a listához</a>
        <a href="{{ route('favorites.edit', $favoriteCar->id) }}" class="px-4 py-2 rounded-lg bg-accent text-black font-semibold">Szerkesztés</a>
    </div>

</div>

@endsection

@section('scripts')
    @include('components.favorites.index.carousel-script')
@endsection

