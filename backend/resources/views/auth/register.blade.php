@extends('layouts.app')

@section('title', 'Regisztráció')

@section('content')

@include('components.horizontalHeader')

<section class="min-h-screen flex items-center justify-center bg-base px-6 py-20">

  <div class="w-full max-w-md text-center">

    <!-- TITLE -->
    <h2 class="text-4xl font-extrabold tracking-[0.3em] text-accent mb-4">
      REGISTER
    </h2>

    <p class="text-gray-400 tracking-widest text-sm mb-12">
      USER ACCOUNT CREATION
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

    <!-- FORM -->
    <form class="space-y-8" method="POST" action="{{ route('register.post') }}">
      @csrf

      <!-- FULL NAME -->
      <div class="text-left">
        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
          Teljes név
        </label>
        <input 
          name="full_name"
          type="text"
          value="{{ old('full_name') }}"
          class="w-full bg-panel border border-border rounded-lg px-4 py-3 text-gray-200
                 focus:border-accent focus:outline-none tracking-wide"
          placeholder="pl. Kovács János"
          required
        >
      </div>

      <!-- USERNAME -->
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
          placeholder="pl. kovacs.janos"
          required
        >
      </div>

      <!-- PASSWORD -->
      <div class="text-left">
        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
          Jelszó
        </label>

        <div class="relative">
          <input 
            id="password"
            name="password"
            type="password"
            class="w-full bg-panel border border-border rounded-lg px-4 py-3 pr-12 text-gray-200
                   focus:border-accent focus:outline-none tracking-wider"
            placeholder="••••••••"
            required
          >

          <!-- ICON -->
          <button type="button" id="togglePassword"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-accent transition">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
              <circle cx="12" cy="12" r="3" stroke-width="1.5" />
            </svg>
          </button>
        </div>
      </div>

      <!-- PASSWORD CONFIRM -->
      <div class="text-left">
        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">
          Jelszó megerősítése
        </label>

        <div class="relative">
          <input 
            id="passwordConfirm"
            name="password_confirmation"
            type="password"
            class="w-full bg-panel border border-border rounded-lg px-4 py-3 pr-12 text-gray-200
                   focus:border-accent focus:outline-none tracking-wider"
            placeholder="••••••••"
            required
          >

          <!-- ICON -->
          <button type="button" id="togglePasswordConfirm"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-accent transition">
            <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
              <circle cx="12" cy="12" r="3" stroke-width="1.5" />
            </svg>
          </button>
        </div>
      </div>

      <!-- BUTTON (loading komponenssel) -->
      <x-buttons.loading-button text="Regisztráció" />

    </form>

    <p class="text-center text-sm mt-10 text-gray-500 tracking-wide">
      Már van fiókod?
      <a href="{{ route('user.login') }}" class="text-accent hover:underline">Bejelentkezés</a>
    </p>

  </div>

</section>

<!-- PASSWORD TOGGLE SCRIPT -->
<script>
  const toggle = (inputId, iconId) => {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
      input.type = "text";
      icon.style.opacity = "0.6";
    } else {
      input.type = "password";
      icon.style.opacity = "1";
    }
  };

  document.getElementById("togglePassword")
    .addEventListener("click", () => toggle("password", "eyeIcon"));

  document.getElementById("togglePasswordConfirm")
    .addEventListener("click", () => toggle("passwordConfirm", "eyeIconConfirm"));
</script>

@endsection
