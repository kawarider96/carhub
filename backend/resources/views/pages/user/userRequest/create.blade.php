@extends('layouts.baseLayout')

@php($type = request('type', 'missing_brand'))
@section('title', $type === 'delete_account' ? 'Fiók törlésének kérése' : 'Hiányzó márka bejelentése')

@section('page')
<div class="max-w-xl mx-auto space-y-8">

    <h1 class="text-3xl font-bold tracking-wide text-center">
        @if ($type === 'delete_account')
            Fiók <span class="text-accent">törlésének</span> kérése
        @else
            Hiányzó <span class="text-accent">márka</span> bejelentése
        @endif
    </h1>

    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/40 text-red-400 rounded-lg p-3 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-panel border border-border rounded-xl p-6 shadow-card">
        <form method="POST" action="{{ route('user-requests.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="type" value="{{ $type }}" />

            @if ($type === 'missing_brand')
                <div class="space-y-2">
                    <label class="text-sm font-semibold uppercase tracking-wide">Márkanév</label>
                    <input type="text" name="payload[brand]" value="{{ old('payload.brand') }}" placeholder="pl. Rivian"
                        class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm" />
                </div>
            @else
                <div class="text-sm text-gray-300">
                    A kérelem beküldésével jelzed, hogy a fiók törlését kéred. Az adminisztrátor elbírálja a kérelmet.
                </div>
            @endif

            <button class="px-4 py-2 bg-accent text-black rounded-lg font-semibold hover:bg-green-400 transition">
                Beküldés
            </button>
        </form>
    </div>

    <div>
        <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-400 hover:text-accent">Vissza a vezérlőpultra</a>
    </div>

    </div>
@endsection
