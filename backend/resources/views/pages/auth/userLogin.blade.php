@extends('layouts.auth')

@section('title', 'LOGIN')
@section('subtitle', 'USER ACCESS PANEL')
@section('action', route('auth.login.post'))

@section('form')
    <x-auth.input name="username" label="Felhasználónév" />
    <x-auth.input name="password" type="password" label="Jelszó" />
    <x-shared.loading-button text="Bejelentkezés" />
@endsection

@section('footer')
    <p class="text-gray-500 text-sm tracking-wide">
        Nincs fiókod?
        <a href="{{ route('auth.register') }}" class="text-accent hover:underline">Regisztráció</a>
    </p>
@endsection
