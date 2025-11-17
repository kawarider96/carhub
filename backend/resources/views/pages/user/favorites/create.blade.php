@extends('layouts.baseLayout')

@section('title', 'Új kedvenc autó felvétele')

@section('page')
<div class="min-h-screen flex items-center justify-center bg-base p-6">
    <div class="w-full max-w-2xl bg-panel border border-border rounded-2xl shadow-xl p-8 space-y-8">

        <header class="text-center space-y-2">
            <h1 class="text-3xl font-bold tracking-wide">
                Új <span class="text-accent">kedvenc autó</span> felvétele
            </h1>
        </header>

        <div class="bg-white/5 border border-border rounded-lg p-4">
            <p class="text-sm text-gray-300 mb-3">
                Nem találod a megfelelő modellt a felvenni kívánt autóhoz? Hozz létre újat!
            </p>
            <a href="{{ route('models.create') }}"
               class="inline-block px-4 py-2 bg-accent text-black rounded-md font-semibold hover:bg-green-400 transition">
                Új modell létrehozása
            </a>
        </div>

        <form action="{{ route('favorites.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Márka + Modell --}}
            <div class="grid md:grid-cols-2 gap-6">

                {{-- BRAND --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold uppercase tracking-wide">Márka</label>

                    <div class="relative">
                        <select id="brand" name="brand_id"
                            class="w-full bg-slate-950 border border-slate-700 rounded-lg
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

                    @error('brand_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MODEL --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold uppercase tracking-wide">Modell</label>

                    <div class="relative">
                        <select id="car_model_id" name="car_model_id"
                            class="w-full bg-slate-950 border border-slate-700 rounded-lg
                                   px-3 py-2 pr-12 text-sm focus:ring-2 focus:ring-accent
                                   text-slate-100 appearance-none"
                            disabled>
                            <option value="">Előbb válassz márkát...</option>
                        </select>

                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                             fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 10l5 5 5-5"/>
                        </svg>
                    </div>

                    @error('car_model_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Évjárat – Szín – Üzemanyag --}}
            <div class="grid md:grid-cols-3 gap-6">

                {{-- YEAR --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold uppercase tracking-wide">Évjárat</label>
                    <input type="number"
                           name="year"
                           value="{{ old('year') }}"
                           placeholder="pl. 2018"
                           class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    @error('year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- COLOR --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold uppercase tracking-wide">Szín</label>
                    <input type="text"
                           name="color"
                           value="{{ old('color') }}"
                           placeholder="pl. fekete"
                           class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    @error('color')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FUEL --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold uppercase tracking-wide">Üzemanyag</label>
                    <input type="text"
                           name="fuel"
                           value="{{ old('fuel') }}"
                           placeholder="pl. benzin"
                           class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    @error('fuel')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- BUTTON --}}
            <button type="submit"
                    class="px-6 py-2.5 bg-accent text-slate-900 rounded-lg font-semibold tracking-wide hover:bg-accent/80 transition">
                Mentés
            </button>
        </form>

    </div>
</div>
@endsection

@section('scripts')
    @include('components.favorites.create.model-select-script')
@endsection
