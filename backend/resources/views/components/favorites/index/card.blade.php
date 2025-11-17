@props([
    'id',
    'model',
    'brand',
    'year',
    'color',
    'fuel',
    'images' => collect(),
])

<div class="bg-panel border border-border rounded-xl shadow-card overflow-hidden hover:border-accent transition p-6">

    {{-- Carousel --}}
    @php
        $imageUrls = $images->isNotEmpty()
            ? $images->map(fn($img) => route('favorites.images.show', ['favoriteCar' => $id, 'image' => $img->id]))
            : collect([asset('images/nocar.png')]);
    @endphp

    <x-favorites.index.carousel :id="$id" :images="$imageUrls" />

    {{-- Car details --}}
    <x-favorites.index.details 
        :model="$model"
        :brand="$brand"
        :year="$year"
        :color="$color"
        :fuel="$fuel"
    />

    {{-- Actions --}}
    <x-favorites.index.actions :id="$id" />

</div>
