<div class="relative">
    <select id="brand" name="brand_id"
        class="pointer-events-auto w-full bg-slate-950 border border-slate-700 rounded-lg
               px-3 py-2 pr-12 text-sm focus:ring-2 focus:ring-accent
               text-slate-100 appearance-none">
        <option value="">Válassz...</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>

    <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
         fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M7 10l5 5 5-5"/>
    </svg>
</div>
