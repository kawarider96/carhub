@extends('layouts.baseLayout')

@section('title', 'Új autómodell létrehozása')

@section('page')
<div class="max-w-xl mx-auto space-y-8">

    <h1 class="text-3xl font-bold tracking-wide text-center">
        Új <span class="text-accent">autómodell</span> létrehozása
    </h1>

    {{-- FORM --}}
    <div class="bg-panel border border-border rounded-xl p-6 shadow-card">
        <form id="modelCreateForm" method="POST" action="#" class="space-y-6">
            @csrf

            {{-- BRAND SELECT --}}
            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Márka</label>
                <select id="car_brand_id" name="car_brand_id"
                        class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 pr-12 text-sm focus:ring-2 focus:ring-accent text-slate-100 appearance-none">
                    <option value="">Válassz...</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('car_brand_id') == $brand->id)>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('car_brand_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- MODEL NAME --}}
            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Modell neve</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="pl. Octavia"
                       class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm" />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="px-4 py-2 bg-accent text-black rounded-lg font-semibold hover:bg-green-400 transition">
                Modell létrehozása
            </button>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('modelCreateForm');
        const brandSelect = document.getElementById('car_brand_id');
        const baseAction = "{{ route('brands.models.store', ['brand' => 0]) }}"; // /brands/0/models

        form.addEventListener('submit', (e) => {
            const brandId = brandSelect.value;
            if (!brandId) {
                e.preventDefault();
                alert('Válassz márkát!');
                return false;
            }
            form.action = baseAction.replace('/0/', '/' + brandId + '/');
        });
    });
</script>
@endsection

