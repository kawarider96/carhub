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
    <x-favorites.carousel :id="$id" :images="$images" />

    {{-- Car details --}}
    <x-favorites.details 
        :model="$model"
        :brand="$brand"
        :year="$year"
        :color="$color"
        :fuel="$fuel"
    />

    {{-- Actions --}}
    <x-favorites.actions :id="$id" />

</div>
