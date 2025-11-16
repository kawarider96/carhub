{{-- resources/views/layouts/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', $title ?? 'Dashboard')

@section('content')

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-panel border-r border-border">
        @include('components.ui.verticalHeader')
    </aside>

    {{-- PAGE CONTENT --}}
    <main class="flex-1 p-10">
        @yield('page')
    </main>

</div>

@endsection
