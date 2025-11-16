@extends('layouts.baseLayout')

@section('page')

<main class="ml-64 p-10 space-y-10">

    {{-- TITLE --}}
    <x-favorites.title />

    {{-- ADD BUTTON --}}
    <x-favorites.add-button />

    {{-- GRID --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 pt-4">

        @forelse ($favorites as $car)
            <x-favorites.card
                :id="$car->id"
                :model="$car->carModel->name"
                :brand="$car->carModel->brand->name"
                :year="$car->year"
                :color="$car->color"
                :fuel="$car->fuel"
                :images="$car->images"
            />
        @empty
            <p class="text-gray-500">Még nincs rögzített kedvenc autód.</p>
        @endforelse

    </div>

</main>

@endsection
