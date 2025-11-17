# Telepítési útmutató (Docker alapú)

## Indítás

1. Töltsd le és telepítsd a legfrissebb Docker alkalmazást:
   https://www.docker.com/products/docker-desktop

2. Klónozd a projektet:
   git clone https://github.com/kawarider96/carhub.git
   cd carhub

3. hozz létre a backend mappában és a fő könyvtárban is egy .env fájlt és másold bele mindkét mappában megtalálható a .env.example fájl tartalmát

4. Lépj be a backend mappába és futtasd a következő parancsot
   composer install
   (ha nincs composer a host gépen akkor telepiteni kell: https://getcomposer.org/download/)

5. Indítsd el a konténereket:
   docker-compose up --build (a carhub könyvtárból kell kiadni a parancsot)

6. Ha a docker compose up már lefutott és sikeresen fut akkor a carhub könyvtárból lépj be a docker konténerbe és generáld le az app key-t:

   docker exec -it carhub_app sh

   majd

   php artisan key:generate

7. Az alkalmazás elérhető:
   http://localhost:8000

(figyelj a port ütközésekre)

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
