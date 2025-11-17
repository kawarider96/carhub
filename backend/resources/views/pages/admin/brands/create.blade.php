@extends('layouts.baseLayout')

@section('title', 'Új márka')

@section('page')
<div class="max-w-md mx-auto space-y-8">
    <h1 class="text-3xl font-bold tracking-wide">Új <span class="text-accent">márka</span> létrehozása</h1>

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
        <form method="POST" action="{{ route('admin.brands.store') }}" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Márkanév</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>
            <button class="px-4 py-2 bg-accent text-black rounded-lg font-semibold hover:bg-green-400 transition">Létrehozás</button>
        </form>
    </div>
</div>
@endsection

