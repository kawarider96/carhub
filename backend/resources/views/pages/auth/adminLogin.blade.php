@extends('layouts.auth')

@section('title', 'ADMIN')
@section('subtitle', 'AUTHORIZED PERSONNEL ONLY')
@section('warning', 'Jogosulatlan hozzáférési kísérlet naplózásra kerül.')
@section('action', route('admin.login.post'))

@section('form')
    <x-auth.input name="username" label="Admin azonosító" />
    <x-auth.input name="password" type="password" label="Admin jelszó" />
    <x-shared.loading-button text="Bejelentkezés" />
@endsection

@section('footer')
    <p class="text-gray-500 text-xs tracking-wide">
        Rendszer hozzáférési szint: <span class="text-accent">Admin</span>
    </p>
@endsection
