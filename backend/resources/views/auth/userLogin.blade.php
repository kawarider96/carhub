@extends('layouts.app')

@section('title', 'Bejelentkezés')

@section('content')

@include('components.horizontalHeader')

<!-- USER LOGIN PAGE – VERZIÓ B -->
<section class="min-h-screen flex items-center justify-center bg-base px-6 py-20">

    <div class="w-full max-w-md text-center">

        <!-- TITLE -->
        <h2 class="text-4xl font-extrabold tracking-[0.4em] text-accent mb-4">
            LOGIN
        </h2>

        <p class="text-gray-400 tracking-widest text-sm mb-12">
            USER ACCESS PANEL
        </p>

        {{-- Hibák --}}
        @if ($errors->any())
            <div class="mb-6 text-left">
                <div class="text-red-400 text-sm font-semibold mb-2">Hiba történt:</div>
                <ul class="list-disc list-inside text-red-500 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Sikerüzenet --}}
        @if (session('success'))
            <div class="mb-6 text-left text-green-400 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- FORM -->
        <form class="space-y-8" method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="text-left">
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
                    Felhasználónév
                </label>
                <input
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    class="w-full bg-panel border border-border rounded-lg px-4 py-3 text-gray-200
                           focus:border-accent focus:outline-none tracking-wide"
                    placeholder="user id…"
                    required
                >
            </div>

            <div class="text-left">
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
                    Jelszó
                </label>
                <input
                    name="password"
                    type="password"
                    class="w-full bg-panel border border-border rounded-lg px-4 py-3 text-gray-200
                           focus:border-accent focus:outline-none tracking-wider"
                    placeholder="••••••••"
                    required
                >
            </div>

            <!-- BUTTON (loading komponenssel) -->
            <x-buttons.loading-button text="Bejelentkezés" />

        </form>

        <p class="text-gray-500 text-sm mt-10 tracking-wide">
            Nincs fiókod?
            <a href="{{ route('user.register') }}" class="text-accent hover:underline">Regisztráció</a>
        </p>

    </div>

</section>

@endsection
