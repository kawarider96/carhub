@extends('layouts.baseLayout')

@section('title', 'Felhasználó szerkesztése')

@section('page')
<div class="max-w-xl mx-auto space-y-8">
    <h1 class="text-3xl font-bold tracking-wide">Felhasználó <span class="text-accent">szerkesztése</span></h1>

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
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Teljes név</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Felhasználónév</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Szerepkör</label>
                <select name="role" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="user" @selected(old('role', $user->role)==='user')>User</option>
                    <option value="admin" @selected(old('role', $user->role)==='admin')>Admin</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Aktív</label>
                <select name="is_active" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="1" @selected(old('is_active', $user->is_active))>Igen</option>
                    <option value="0" @selected(!old('is_active', $user->is_active))>Nem</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Sikertelen belépések</label>
                <input type="number" min="0" name="failed_logins" value="{{ old('failed_logins', $user->failed_logins) }}" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <hr class="border-border" />

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Új jelszó (opcionális)</label>
                <input type="password" name="password" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold uppercase tracking-wide">Új jelszó megerősítése</label>
                <input type="password" name="password_confirmation" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex gap-3">
                <button class="px-4 py-2 bg-accent text-black rounded-lg font-semibold hover:bg-green-400 transition">Mentés</button>
                <a href="{{ route('admin.dashboard.index') }}" class="px-4 py-2 bg-white/5 border border-border rounded-lg hover:border-accent transition">Mégse</a>
            </div>
        </form>
    </div>

</div>
@endsection
