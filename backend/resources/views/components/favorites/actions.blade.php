@props(['id'])

<div class="pt-4 flex gap-3">

    <a href="{{ route('favorites.show', $id) }}"
       class="px-4 py-2 rounded-lg bg-accent text-black font-semibold hover:bg-green-400 transition">
        Megtekintés
    </a>

    <a href="{{ route('favorites.edit', $id) }}"
       class="px-4 py-2 rounded-lg bg-white/5 text-gray-200 border border-border 
              hover:border-accent hover:text-accent transition">
        Szerkesztés
    </a>

    <form method="POST" action="{{ route('favorites.destroy', $id) }}"
          onsubmit="return confirm('Biztosan törlöd?')" class="inline">
        @csrf
        @method('DELETE')
        
        <button
           class="px-4 py-2 rounded-lg bg-white/5 text-red-500 border border-red-500/40 
                  hover:border-red-500 hover:bg-red-500/10 transition">
            Törlés
        </button>
    </form>

</div>
