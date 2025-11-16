@extends('layouts.auth')

@section('title', 'REGISTER')
@section('subtitle', 'USER ACCOUNT CREATION')
@section('action', route('auth.register.post'))

@section('form')
    <x-auth.input name="full_name" label="Teljes név" />
    <x-auth.input name="username" label="Felhasználónév" />
    <x-auth.input name="password" type="password" label="Jelszó" />
    <x-auth.input name="password_confirmation" type="password" label="Jelszó megerősítése" />
    <x-shared.loading-button text="Regisztráció" />
@endsection

@section('footer')
    <p class="text-sm text-gray-500 tracking-wide">
        Már van fiókod?
        <a href="{{ route('auth.login') }}" class="text-accent hover:underline">Bejelentkezés</a>
    </p>
@endsection
