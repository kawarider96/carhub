@extends('layouts.app')

@section('title', 'Kezdőlap')

@section('content')

@include('components.ui.horizontalHeader')

<!-- HERO SECTION -->
<section class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-14 items-center">

    <!-- TEXT -->
    <div class="space-y-6">
        <h2 class="text-4xl font-extrabold leading-tight">
            Szolgálati járművek nyilvántartása.  
            <span class="text-accent">Biztonságos, belső rendszerben.</span>
        </h2>

        <p class="text-gray-300 leading-relaxed text-lg">
            A SARS egy belső használatra fejlesztett járműkezelő rendszer, amely lehetővé teszi a felhasználóknak,
            hogy nyilvántartsák és dokumentálják a szolgálati járműveiket. A rendszer támogatja a márkák és modellek
            struktúrált kezelését, valamint a járműadatok részletes rögzítését. Az adminisztrátorok külön felületen
            kezelhetik a felhasználókat, jóváhagyhatják a törlési kérelmeket és bővíthetik a járműmárka listát.
            A SARS célja egy átlátható, biztonságos és auditálható infrastruktúra biztosítása a szervezet járműállományának kezeléséhez.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a href="{{ route('auth.login') }}"
            class="bg-accent text-black px-6 py-3 rounded-xl font-semibold shadow hover:bg-green-400 transition text-center">
                Felhasználói bejelentkezés
            </a>

            <a href="{{ route('admin.login') }}"
            class="bg-panel px-6 py-3 rounded-xl font-semibold shadow border border-border hover:border-accent transition text-center">
                Admin belépés
            </a>
        </div>
    </div>

    <!-- IMAGE -->
    <div>
        <img src="https://images.unsplash.com/photo-1616422285623-13ff0162193c?q=80&w=862&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
             class="rounded-xl shadow-card border border-border"
             alt="Szolgálati jármű">
    </div>

</section>

@endsection
