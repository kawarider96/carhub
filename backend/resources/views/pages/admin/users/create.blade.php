@extends('layouts.baseLayout')

@section('title', 'Új felhasználó')

@section('page')
<div class="max-w-md mx-auto space-y-8">
    <h1 class="text-3xl font-bold tracking-wide">Új <span class="text-accent">felhasználó</span> létrehozása</h1>

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
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Teljes név</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Felhasználónév</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Jelszó</label>
                <input type="password" name="password" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Jelszó megerősítése</label>
                <input type="password" name="password_confirmation" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Szerepkör</label>
                <select name="role" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="user" @selected(old('role')==='user')>User</option>
                    <option value="admin" @selected(old('role')==='admin')>Admin</option>
                </select>
            </div>

            <button class="px-4 py-2 bg-accent text-black rounded-lg font-semibold hover:bg-green-400 transition">Létrehozás</button>
        </form>
    </div>
</div>
@endsection

