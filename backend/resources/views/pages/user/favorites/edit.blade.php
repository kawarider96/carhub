@extends('layouts.baseLayout')

@section('title', 'Kedvenc autó szerkesztése')

@section('page')
<div class="max-w-4xl mx-auto space-y-10">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold tracking-wide text-center">
        Kedvenc autó <span class="text-emerald-400">szerkesztése</span>
    </h1>


    <!-- ========================= -->
    <!-- CAROUSEL -->
    <!-- ========================= -->

    <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-6 shadow-xl">

        <div class="relative w-full aspect-[4/3] overflow-hidden rounded-lg border border-slate-700 mb-4"
             data-carousel-id="1">

            @php
                $imageUrls = $images->isNotEmpty()
                    ? $images->map(fn($img) => route('favorites.images.show', ['favoriteCar' => $favoriteCar->id, 'image' => $img->id]))->toArray()
                    : [asset('images/nocar.png')];
            @endphp

            <!-- MAIN IMAGE -->
            <img 
                data-main="1"
                id="mainImage1"
                src="{{ $imageUrls[0] }}"
                class="w-full h-full object-cover transition-opacity duration-300 opacity-100"
            >

            <!-- ARROWS -->
            <button onclick="prevImage(1)"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full">
                ‹
            </button>

            <button onclick="nextImage(1)"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full">
                ›
            </button>

            <!-- THUMBS -->
            <div id="thumbs1" class="absolute bottom-2 right-2 flex gap-2 bg-black/50 p-2 rounded-lg">
                @foreach($imageUrls as $url)
                    <img data-thumb 
                        src="{{ $url }}"
                        class="w-12 h-12 cursor-pointer rounded-md opacity-70 hover:opacity-100"
                        onclick="setImage(1, '{{ $url }}')"
                    >
                @endforeach
            </div>

        </div>
    </div>



    <!-- ========================= -->
    <!-- FORM #1 – FavoriteCar UPDATE -->
    <!-- ========================= -->

    <div class="bg-slate-900/70 border border-slate-700 rounded-xl p-6 space-y-6">

        <h2 class="text-xl font-semibold">Autó adatainak módosítása</h2>

        <form 
            action="{{ route('favorites.update', $favoriteCar->id) }}" 
            method="POST"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                @include('components.favorites.edit.brand-select', [
                    'brands' => $brands,
                    'favoriteCar' => $favoriteCar
                ])

                @include('components.favorites.edit.model-select', [
                    'favoriteCar' => $favoriteCar
                ])
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-semibold">Évjárat</label>
                    <input 
                        type="number"
                        name="year"
                        value="{{ old('year', $favoriteCar->year) }}"
                        class="w-full p-2 rounded bg-slate-800 border border-slate-700">
                </div>

                <div>
                    <label class="text-sm font-semibold">Szín</label>
                    <input 
                        type="text" 
                        name="color"
                        value="{{ old('color', $favoriteCar->color) }}"
                        class="w-full p-2 rounded bg-slate-800 border border-slate-700">
                </div>

                <div>
                    <label class="text-sm font-semibold">Üzemanyag</label>
                    <input 
                        type="text" 
                        name="fuel"
                        value="{{ old('fuel', $favoriteCar->fuel) }}"
                        class="w-full p-2 rounded bg-slate-800 border border-slate-700">
                </div>
            </div>

            <button 
                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg font-semibold">
                Autó módosítása
            </button>

        </form>

    </div>




    <!-- ========================= -->
    <!-- FORM #2 – CarImage Upload -->
    <!-- ========================= -->

    <div class="bg-slate-900/70 border border-slate-700 rounded-xl p-6 space-y-6">
        <h2 class="text-xl font-semibold">Képek kezelése</h2>

        <form 
            action="{{ route('favorites.images.store', ['favoriteCar' => $favoriteCar->id]) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            @csrf

            <label class="text-sm font-semibold block">Új képek feltöltése</label>

            <!-- FILE PICKER BUTTON -->
            <button 
                type="button"
                id="openFilePicker"
                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-lg">
                Fájlok kiválasztása
            </button>

            <!-- REAL INPUT (HIDDEN) -->
            <input 
                type="file" 
                id="imageInput"
                name="images[]"
                multiple
                class="hidden"
            />

            <!-- PREVIEW -->
            <div id="previewContainer" class="flex gap-3 flex-wrap mt-3"></div>

            <!-- SUBMIT -->
            <button 
                type="submit"
                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg font-semibold">
                Képek feltöltése
            </button>

        </form>
    </div>
</div>
@endsection

@section('scripts')
    @include('components.favorites.edit.model-select-script')
    @include('components.favorites.edit.image-preview')
    @include('components.favorites.index.carousel-script')

     <script>
document.querySelector('form[action*="images"]').addEventListener('submit', function(e) {
    console.log("FORM SUBMITTED!");

    const formData = new FormData(this);

    console.log("FORMDATA DUMP:");

    // dump all keys + values
    for (let pair of formData.entries()) {
        console.log(pair[0], pair[1]);
    }
});
</script>
@endsection

