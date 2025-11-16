@extends('layouts.app')

@section('title', 'Admin belépés')

@section('content')

@include('components.horizontalHeader')

<!-- ADMIN LOGIN PAGE – RESTRICTED TERMINAL -->
<section class="min-h-screen flex items-center justify-center bg-base px-6 py-20">

  <div class="w-full max-w-md text-center">

    <!-- TITLE -->
    <h2 class="text-4xl font-extrabold tracking-[0.4em] text-accent mb-4">
      ADMIN
    </h2>

    <p class="text-gray-400 tracking-widest text-sm mb-2">
      AUTHORIZED PERSONNEL ONLY
    </p>

    <!-- RED WARNING -->
    <p class="text-red-500 text-xs tracking-wider mb-12">
      Jogosulatlan hozzáférési kísérlet naplózásra kerül.
    </p>

    <!-- ERRORS -->
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

    @if (session('success'))
      <div class="mb-6 text-left text-green-400 text-sm font-semibold">
        {{ session('success') }}
      </div>
    @endif

    <!-- FORM -->
    <form class="space-y-8" method="POST" action="{{ route('admin.login.post') }}">
      @csrf

      <div class="text-left">
        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
          Admin azonosító
        </label>
        <input
          name="username"
          type="text"
          value="{{ old('username') }}"
          class="w-full bg-panel border border-border rounded-lg px-4 py-3 text-gray-200
                 focus:border-accent focus:outline-none tracking-wide"
          placeholder="admin username"
          required
        >
      </div>

      <div class="text-left">
        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
          Admin jelszó
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

    <!-- FOOTNOTE -->
    <p class="text-gray-500 text-xs mt-10 tracking-wide">
      Rendszer hozzáférési szint: <span class="text-accent">Admin</span>
    </p>

  </div>

</section>

@endsection
