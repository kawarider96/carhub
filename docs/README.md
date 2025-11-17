# CarHub – Szolgálati járművek nyilvántartása

A CarHub egy belső használatra szánt járműnyilvántartó rendszer, amely lehetővé teszi a felhasználók számára, hogy saját kedvenc járműveiket rögzítsék, képeket töltsenek fel hozzájuk, valamint különböző adminisztrációs kérelmeket indítsanak. Az admin felhasználók pedig kezelhetik az autómárkákat, jóváhagyhatják a törlési kérelmeket és zárolásokat oldhatnak fel.

##  Fő funkciók

- Bejelentkezés / regisztráció kétféle szerepkörrel (admin, user)
- Fiók zárolása 5 hibás jelszókísérlet után
- Kedvenc autók kezelése (CRUD)
- Autómárkák és típusok kezelése
- Képek feltöltése kedvenc autókhoz
- Törlési kérelmek indítása és admin általi jóváhagyás
- Hiányzó autómárka bejelentése adminnak
- Teljes REST API + webes felület
- Role-based jogosultságkezelés policies és middleware használatával

##  Telepítés

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


Bejelentkezéshez alapértelmezett admin user

Felhasználónév: Admin

Jelszó: Admin123!

Dokumentáció

OPENAPI DOCS : http://localhost:8000/docs/api

docs/felhasznaloi-utasitas.md

docs/admin-utasitas.md

docs/telepitesi-utmutato.md

docs/database-diagram : https://dbdiagram.io/d/carhub-6913008d6735e11170392731

ROLES.md
Tesztelés
lépj be a docker konténerbe:

1. docker exec -it carhub_app sh

2. php artisan test


Több mint 300 automatikus teszt a policies, service logika és controller viselkedés lefedésére
Biztonság

Jelszavak bcrypt hash-selve

CSRF védelem minden formon

SQL injection védelem Eloquent ORM használatával

Account lock 5 sikertelen bejelentkezés után

Role-based access policies minden modellhez


---

# `ROLES.md`

# Szerepkörök és jogosultságok

Az alkalmazás kétféle felhasználói szerepkört különböztet meg: `user` és `admin`. Az alábbi táblázat mutatja, hogy az egyes szerepkörök mit tehetnek meg az alkalmazásban.

| Funkció                                             | User            | Admin           |
|-----------------------------------------------------|------------------|------------------|
| Regisztráció / Bejelentkezés                        | ✅               | ✅               |
| Kedvenc autók listázása / felvitele / módosítása    | ✅               | ❌               |
| Képek feltöltése kedvenc autóhoz                    | ✅               | ❌               |
| Típus hozzáadása meglévő márkához                   | ✅               | ❌               |
| Autómárka hiány bejelentése                         | ✅               | ❌               |
| Fiók törlési kérelem indítása                       | ✅               | ❌               |
| Saját fiók törlése közvetlenül                      | ❌               | ❌               |
| Autómárkák kezelése (CRUD)                          | ❌               | ✅               |
| Felhasználók listázása / szerkesztése               | ❌               | ✅               |
| Admin jog adása más usernek                         | ❌               | ✅               |
| Törlési és model-kérelem lista kezelése             | ❌               | ✅               |
| Fiók zárolásának feloldása                          | ❌               | ✅               |
| Kedvenc autók megtekintése más user nevében         | ❌               | ✅               |
| Típusok hozzáadása a márkához                       | ✅               | ❌               |

## Technikai megvalósítás

- Jogosultság ellenőrzés **Policy osztályokkal** történik (pl. `FavoriteCarPolicy`, `UserPolicy`)
- Route middleware használva:
  - `auth` → csak bejelentkezett felhasználóknak
  - `admin` → csak `admin` szerepkörrel rendelkező felhasználóknak
  - `active` → csak aktív azaz nem zárolt fiókkal rendelkező felhasználóknak

## Példa: Middleware alapú védelem

```php
Route::middleware(['auth', 'active'])->group(function () {
    Route::resource('car-brands', CarBrandController::class);
});

Megjegyzés

A user típusú és zárolt felhasználók nem férhetnek hozzá semmilyen más felhasználó adatához vagy admin funkcióhoz. Az admin minden entitást lát és módosíthat. (AZ ADMIN MIDDLEWARE CSAK A CONTROLLEREKBEN VAN IMPELENTÁLVA!)
