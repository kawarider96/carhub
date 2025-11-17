Bármilyen programozási nyelven (PHP, C# előnyben. adatbázisok: mysql, mssql, sqlite, postgres)

Készítsen webalkalmazást vagy asztali alkalmazást a következő funkciókkal:

    - kezdőlap (kb 5 mondatban leírás mit valósít meg az alkalmazás, egy témába illő kép) ami bemutatja az alkalmazást (1 pont)
    - bejelentkezési lehetőség (külön az adminnak és külön a sima usernek. a kezdőlapról legyen redirect, gomb stb lehetőség a bejelentkezésre)  (2 pont)
    - admin felület az alkalmazás felhasználóinak listázására, új felhasználó felvételére, szerkesztésére, törlésére (továbbiakban CRUD) (8 pont)
    - a felhasználók törlése csak akkor valósulhat meg, ha azt egy felhasználó elindította. ő saját maga nem tudja törölni a fiókot. (5 pont)
a felhasználó igény indítását követően az adminnak van lehetősége törölni a felhasználót. (5 pont)
    - a felhasználók nyilván tudják tartani a kedvenc autóikat (CRUD) (8 pont)
    - az admin felhasználók tudnak felvenni autó márkákat egy másik adatmodellben, amit a felhasználók ki tudnak választani maguknak (5 pont)
    - ha valamilyen autó márka nincs, akkor valamilyen módszerrel képes értesíteni az admin-t, hogy az vegyen fel új márkát (5 pont)
(a törlés és ez a funkció lehet ugyanazon a logikán alapulva)

Bejelentkezés
    - felhasználónév és jelszó megadásával, csak az aktív felhasználók tudnak bejelentkezni (3 pont)
    - 5 elrontott jelszó után zárolja az accountot a rendszer, azt csak az admin tudja feloldalni (4 pont)
    - kijelentkezési lehetőség (2 pont)
    - a felhasználóknak legyen regisztrációs lehetősége, a regisztráció során tudjon megadni nevet, felhasználónevet és jelszót. (4 pont)
    - a jelszó megadáshoz a már megszokott validációs eljárásokat elvárjuk (jelszó kétszer, megfelelő bonyolultság és hosszúság.
minimum 8 karakter és kis nagy betű szám spec karakter) (4 pont)

Admin felület
    - csak az "admin" típusú felhasználó érheti el (2 pont)
 -   - a kezdeti admin felhasználót az adatbázisban hozza létre kézzel vagy valamilyen script segítségével,
amit a webserveren vagy programból csak úgy nem lehet elérni. ergo ez legyen egy adminisztrátori lépés, vagy legelső indulásnál a szoftver tegye meg a megfelelő admin bejelentkezés
létrehozást, vagyis minimum legyen egy admin felhasználó. (4 pont / előre beégetve az adatbázisba esetén 1 pont max)
    - listázza ki a felhasználókat (lásd feljebb 8 pontban beleszámít)
    - a felhasználók állapotát, zárolását stb. (lásd feljebb 8 pontban beleszámít)
    - legyen egy rész, ahol a felhasználói kérések vannak nyilvántartva. törlési lehetőség zárolások visszaoldása a korábban már leírt folyamat szerint,
vagyis mindegyiket előzze meg egy felhasználói akció. (lásd feljebb 5 pontban beleszámít)
    - új felhasználó rögzítésének lehetősége admin oldalról is legyen meg. a felhasználókat az admin képes legyen admin típusra beállítani.
(ha az admin is képes felhasználót feltölteni akkor további plusz 2 pont)

Autó nyilvántartási rész.
    - a felhasználók tudjanak egy lenyíló vezérlőből kiválasztani autó márkát. (lásd feljebb 8 pontban beleszámít)
    - az autó márkához a felhasználó tudjon típust rögzíteni. a már rögzített típus a későbbi feltöltések esetén jelenjen meg. vagyis szótár tábla szerűen legyen rögzítve.
adatbázisban legyenek meg a megfelelő kapcsolódások a táblák között. (lásd feljebb 8 pontban beleszámít)
    - az autó típus mellé tudjon rögzíteni egy évjáratot (int), egy színt (text), egy üzemanyag típust (text) ezek nem kell,
hogy szótár tábla szerűen legyenek tárolva. (lásd feljebb 8 pontban beleszámít)
    - további opcióként minden felhasználó minden autóhoz tudjon rögzíteni képeket (bármennyi, varbinary típus).
értelemszerűen figyelni kell az adatbázis kapcsolatra. egy autóhoz-sok kép. (lásd feljebb 8 pontban beleszámít, illetve lejjebb az adattáblák pontjainál)

Felhasználók adatai: (1 pont)
- felhasználónév
- jelszó
- felhasználó típus (admin, user)
- teljes név
- aktív-e a felhasználói fiók

Autó márka szótár: (1 pont)
- azonosító
- megnevezés

Autó típus szótár: (1 pont)
-azonosító
- autó_márka_azonosító
- megnevezés

Kedvenc autók tábla: (1 pont)
- azonosító
- autó_tipus_azonositó
- felhasználó_azonositó
- évjárat
- szín
- üzemanyag

Autó képek tábla: (1 pont)
- azonosító
- kedvenc_autó_azonositó
- kep_tartalom
Megfelelő primary key, auto increment, foreign key (5 pont)

Biztonsági követelmények (fontossági sorrendben):
- az alkalmazás minden oldala csak bejelentkezés után érhető el
    ez alól kivétel a kezdő oldal és bejelentkezési oldalak (ha a login külön oldalon van megoldva és nem modal szerűen) (4 pont)
- felhasználói nevek és jelszavak hashelve legyenek tárolva (értelem szerűen a bejelentkezést követően a felhasználó teljes neve jelenjen meg mindenhol) (5 pont)
- sql injection elleni védelem (2 pont)
- minden beviteli mező, form stb esetén megfelelő validáció legyen a vezérlőkön, amelyek tükrözzék, hogy az adat táblák oszlopainak típusát vagy hosszát (text esetén)
a validációnál nem elég kliens oldali validáció, elvárjuk a szerver oldali, vagy code-behind validációt is. (4 pont)
- formok esetén csrf elleni védelem (2 pont)

A feladat során a következő irányelveket tartsa szem előtt

- separation of concerns (2 pont)
- megfelelő szintű programozási módszertanok / eljárások alkalmazása, amit a feladat indokol (és az idő enged) (opcionális 2 pont)
- lehetőség szerint a HTTP protokoll adta lehetőségek kihasználása (weboldal létrehozása esetén) (2 pont)
- átlátható, olvasható kódolás, kommentelés mindenhol. adatbázisban nem szükséges. (3 pont)
- adatbázis táblákról egy database diagram szerű ábrát kérünk. (1 pont)
- hatékonyság, egyszerű, újra felhasználható kód. (2 pont)
- következetes (tetszőleges) névadási stratégia. az adattábláknál, függvényeknél, osztályoknál értelemszerűen valamilyen rendszer (case notation) elnevezés szabadon választott.
(1 pont)

Egyéb

- tetszőleges framework használata elfogadott, amíg hasonló végeredményt önállóan is el tudna készíteni (opcionális 5 pont)
- közös layout / masterpage használata (header, fő navigációs menü, footer weboldal esetén. desktop esetén usercontrollok) (opcionális 5 pont)
- HTML szemantikus elemeinek használata   (opcionális 2 pont)
- reszponzivitás növelése (javascript alkalamazása), design (igényesebb css) inkább a funkcionalitás (opcionális 2 pont)
    implementálása után
- célszerűen bootstrap használata, ha nincs nagy gyakorlat a html, javascript, css terén. (opcionális 2 pont)


A cél, hogy a szerver oldali kódok, adatbázis felépítése és a kliens oldalról való adatok olvasásása megfelelően történjen.
Elvárunk egy megfelelő  dokumentáltságot. Felhasználói utasítás. Admin utasítás. Telepítési utasítás.
Ezek hiányában a következő pont levonásokra lehet számítani: minusz 4-4-4 pont.

Összesen alap pontokkal: 100 pontot lehet elérni.
Lehet szerezni opcionálisan: plusz 20 pontot.
Lehet szerezni büntető pontokat: mínusz 12 pontot.

Az eredmény tekintetében 100 pontot vesszük alapul és 75 ponttól számít megfelelőnek a teszt.
 