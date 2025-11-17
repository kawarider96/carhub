<div class="grid md:grid-cols-3 gap-6">
    {{-- Évjárat --}}
    <div class="space-y-2">
        <label class="text-sm font-semibold uppercase tracking-wide">Évjárat</label>
        <input type="number" name="year" placeholder="pl. 2018"
               value="{{ old('year') }}"
               class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
        @error('year')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Szín --}}
    <div class="space-y-2">
        <label class="text-sm font-semibold uppercase tracking-wide">Szín</label>
        <input type="text" name="color" placeholder="pl. fekete"
               value="{{ old('color') }}"
               class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
        @error('color')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Üzemanyag --}}
    <div class="space-y-2">
        <label class="text-sm font-semibold uppercase tracking-wide">Üzemanyag</label>
        <input type="text" name="fuel" placeholder="pl. benzin"
               value="{{ old('fuel') }}"
               class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
        @error('fuel')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
