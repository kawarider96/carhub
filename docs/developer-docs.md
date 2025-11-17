#  Developer Notes – CarHub

Ez a dokumentum a CarHub rendszer fejlesztői szempontú áttekintését tartalmazza. Leírja az architektúrát, fejlesztési mintákat, technikai döntéseket és egyéb háttérfolyamatokat.

---

## Architektúra

A rendszer **Domain-Driven Design** alapokra épül, ahol az alábbi rétegek határolják el egymást:

- **Controllers** – belépési pont a web és API kérésekhez
- **Services** – üzleti logika, validálás, kontrollált feldolgozás
- **Repositories** – adatbázis műveletek (Eloquent ORM)
- **Policies** – jogosultságok modell szinten
- **Middleware** – route szintű hozzáférés szabályozás

---

## Tech Stack

- **PHP 8.3**
- **Laravel 12.x**
- **MySQL (MariaDB)**
- **Docker + Compose**
- **Blade + Tailwind (CDN-alapú)**
- **PHPUnit (több mint 300+ teszt)**

---

## Kódszerkezet

app/
├── Http/
│ ├── Controllers/ # Web és API vezérlők (szétválasztva)
│ ├── Middleware/ # 'active' és 'admin' middleware
│
├── Services/ # Minden domain entitáshoz saját Service
├── Repositories/ # Minden modellhez tartozó repository
├── Policies/ # Role alapú jogosultságok (policy-k)
│
├── Traits/
│ └── ApiResponse.php # Egységes success/error JSON válasz wrapper

---

## Tesztelés

A tesztek három réteget fednek le:

- ✅ **Service tesztek** – Üzleti logika (pl. `FavoriteCarServiceTest`)
- ✅ **Policy tesztek** – Jogosultsági szintek (pl. `UserPolicyTest`)
- ✅ **Feature tesztek** – Végpontok viselkedése (pl. `FavoriteCarControllerTest`)

A `tests/` mappában strukturáltan van felépítve:
- `Unit/Services`
- `Unit/Policies`
- `Feature/Controllers`

---

## Biztonság és jogosultság

- **Middleware**
  - `auth` – bejelentkezés szükséges
  - `active` – csak aktív fiókok férnek hozzá
  - `admin` – kizárólag admin role

- **Policy**
  - Amelyik entitáshoz szükséges egyedi policy (pl. `FavoriteCarPolicy`)

---

## Routing

A Laravel 12 új `bootstrap/app.php` fájlban a routing így van konfigurálva:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    ...
)
Az Exceptions::render() szintjén egyéni kezelés történik AuthenticationException esetére, külön JSON és redirect elágazással.

Frontend
A Blade alapú UI külön layoutokkal dolgozik:

layouts.app – alap layout

layouts.auth – bejelentkezés/regisztráció

layouts.base – oldalsávos admin/ user felület

Komponensek (resources/views/components/) használata:

Docker automatizáció
A app konténer automatikusan futtatja az alábbiakat induláskor:

php artisan migrate --seed
Így létrejön az adatbázis, táblák, admin user, márkák, modellek, stb.

Egyedi logika
Fiók zárolása: 5 sikertelen próbálkozás után a is_active mező false lesz.

Törlési kérelem: A user nem törölheti magát, de kérhet törlést. Az admin jóváhagyja.

Képfeltöltés: többfájlos, car_images.content mező LONGBLOB (base64 encode-olva tárolva).

Egyéb
PHPStan: minden fájl típusozva (repository, service, controller)

Mockery: unit tesztekben a service rétegekhez

Factory + Seeder: minden modellhez létezik gyári adatgenerátor

Fejlesztő:
Királyfalvi Krisztián – 2025