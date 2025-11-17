# Telepítési útmutató (Docker alapú)

## Indítás

1. Töltsd le és telepítsd a legfrissebb Docker alkalmazást:
   https://www.docker.com/products/docker-desktop

2. Klónozd a projektet:
   git clone https://github.com/kawarider96/carhub.git
   cd carhub

3. Indítsd el a konténereket:
   docker-compose up -d

4. Az alkalmazás elérhető:
   http://localhost:8000

## Fontos tudnivalók

A Docker automatikusan futtatja a `php artisan migrate --seed` parancsot az `app` konténer elindulásakor.  
Ennek köszönhetően az adatbázis struktúra létrejön, és az alapértelmezett admin fiók is bekerül.

## Alapértelmezett admin belépési adatok

Felhasználónév: Admin  
Jelszó: Admin123!

## Tesztek futtatása

1. Lépj be az `app` konténerbe:
   docker exec -it carhub_app sh

2. Futtasd le a teszteket:
   php artisan test

A rendszer több mint 300 automatikus tesztet tartalmaz, melyek lefedik a policies, service logika és controller viselkedés különböző eseteit.
