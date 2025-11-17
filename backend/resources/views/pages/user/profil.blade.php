@extends('layouts.baseLayout')

@section('title', 'Profil')

@section('page')
<div class="max-w-2xl mx-auto space-y-8">

    <h1 class="text-3xl font-bold tracking-wide">Felhasználói <span class="text-accent">profil</span></h1>

    @if (session('success'))
        <div class="bg-green-500/10 border border-green-500/40 text-green-400 rounded-lg p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-panel border border-border rounded-xl p-6 shadow-card space-y-2">
        <div><span class="text-gray-400">Név:</span> {{ $user->full_name }}</div>
        <div><span class="text-gray-400">Felhasználónév:</span> {{ $user->username }}</div>
        <div><span class="text-gray-400">Szerepkör:</span> {{ $user->role }}</div>
        <div><span class="text-gray-400">Állapot:</span> {{ $user->locked ? 'Zárolt' : 'Aktív' }}</div>
        <div><span class="text-gray-400">Létrehozva:</span> {{ optional($user->created_at)->format('Y-m-d') }}</div>
        <div><span class="text-gray-400">Utoljára frissítve:</span> {{ optional($user->updated_at)->format('Y-m-d') }}</div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('profile.change-password') }}" class="px-4 py-2 bg-accent text-black rounded-lg font-semibold hover:bg-green-400 transition">
            Jelszó megváltoztatása
        </a>

        <a href="{{ route('user-requests.create', ['type' => 'delete_account']) }}"
           class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-500 transition">
            Fiók törlésének kérése
        </a>
    </div>

</div>
@endsection

