@extends('layouts.app')

@section('content')

@include('components.ui.horizontalHeader')

<section class="min-h-screen flex items-center justify-center bg-base px-6 py-20">

    <div class="w-full max-w-md text-center">

        {{-- TITLE --}}
        <h2 class="text-4xl font-extrabold tracking-[0.3em] text-accent mb-4">
            @yield('title')
        </h2>

        {{-- SUBTITLE --}}
        @hasSection('subtitle')
            <p class="text-gray-400 tracking-widest text-sm mb-2">
                @yield('subtitle')
            </p>
        @endif

        {{-- OPTIONAL WARNING --}}
        @hasSection('warning')
            <p class="text-red-500 text-xs tracking-wider mb-8">
                @yield('warning')
            </p>
        @else
            <div class="mb-8"></div>
        @endif

        {{-- ERROR MESSAGES --}}
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

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="mb-6 text-left text-green-400 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM SLOT --}}
        <form method="POST" action="@yield('action')" class="space-y-8">
            @csrf
            @yield('form')
        </form>

        {{-- FOOTER --}}
        @hasSection('footer')
            <div class="mt-10">
                @yield('footer')
            </div>
        @endif

    </div>

</section>

@endsection
