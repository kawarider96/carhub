<button
    type="submit"
    {{ $attributes->merge([
        'class' =>
            'w-full py-3 rounded-lg font-semibold bg-accent text-black
            hover:bg-green-400 transition tracking-wide shadow-soft
            flex items-center justify-center gap-2 relative'
    ]) }}
    onclick="this.classList.add('loading');"
>
    <!-- Loading spinner -->
    <span class="loader hidden w-5 h-5 border-2 border-black/30 border-t-black rounded-full animate-spin"></span>

    <!-- Text -->
    <span class="btn-text">{{ $text ?? 'Küldés' }}</span>
</button>

<style>
    button.loading .btn-text { display: none; }
    button.loading .loader { display: block; }
</style>
