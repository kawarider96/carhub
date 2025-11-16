@props(['id', 'images' => collect()])

<div class="relative w-full aspect-[4/3] overflow-hidden rounded-lg border border-border mb-4">

    {{-- MAIN IMAGE --}}
    <img id="mainImage{{ $id }}"
         src="{{ $images->isNotEmpty() ? route('favorite.image.show', $images->first()->id) : asset('img/no-image.png') }}"
         class="w-full h-full object-cover object-center transition-opacity duration-300 opacity-100"
         alt="Autó kép">

    {{-- LEFT ARROW --}}
    @if($images->count() > 1)
        <button type="button"
                onclick="prevImage({{ $id }})"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full text-lg leading-none">
            ‹
        </button>

        {{-- RIGHT ARROW --}}
        <button type="button"
                onclick="nextImage({{ $id }})"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full text-lg leading-none">
            ›
        </button>
    @endif

    {{-- THUMBS --}}
    <div id="thumbs{{ $id }}"
         class="absolute bottom-2 right-2 flex gap-2 bg-black/50 p-2 rounded-lg">

        @foreach($images as $image)
            <img 
                 src="{{ route('favorite.image.show', $image->id) }}"
                 class="w-10 h-10 object-cover rounded-md opacity-70 hover:opacity-100 cursor-pointer transition"
                 onclick="setImage({{ $id }}, '{{ route('favorite.image.show', $image->id) }}')"
            >
        @endforeach

    </div>

</div>
