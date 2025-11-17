<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Kedvenc autó – Megtekintés</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .fade {
            transition: opacity 0.3s ease;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 p-10">

<div class="max-w-4xl mx-auto space-y-12">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold tracking-wide text-center">
        Kedvenc autó <span class="text-emerald-400">megtekintése</span>
    </h1>

    <!-- ========================= -->
    <!-- CAROUSEL -->
    <!-- ========================= -->
    <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-6 shadow-xl">

        <div class="relative w-full aspect-[4/3] overflow-hidden rounded-lg border border-slate-700 mb-4" data-carousel-id="1">

            <!-- JS-ben felülírjuk majd -->
            <img id="mainImage1"
                 class="w-full h-full object-cover transition-opacity duration-300 fade opacity-100"
                 src="https://via.placeholder.com/800x600?text=Nincs+kép">

            <!-- ARROWS -->
            <button onclick="prevImage(1)"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full">
                ‹
            </button>

            <button onclick="nextImage(1)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full">
                ›
            </button>

            <!-- THUMBNAILS -->
            <div id="thumbs1" class="absolute bottom-2 right-2 flex gap-2 bg-black/50 p-2 rounded-lg"></div>

        </div>
    </div>

    <!-- ========================= -->
    <!-- DETAILS -->
    <!-- ========================= -->

    <div class="bg-slate-900/80 border border-slate-700 rounded-xl p-6 shadow-xl space-y-4">
        <h2 class="text-xl font-semibold">Autó adatai</h2>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-slate-800 rounded-lg border border-slate-700">
                <div class="text-sm opacity-60">Márka</div>
                <div class="text-lg font-semibold" id="brandName">Toyota</div>
            </div>

            <div class="p-4 bg-slate-800 rounded-lg border border-slate-700">
                <div class="text-sm opacity-60">Típus</div>
                <div class="text-lg font-semibold" id="modelName">Supra</div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-slate-800 rounded-lg border border-slate-700">
                <div class="text-sm opacity-60">Évjárat</div>
                <div class="text-lg font-semibold" id="year">1999</div>
            </div>

            <div class="p-4 bg-slate-800 rounded-lg border border-slate-700">
                <div class="text-sm opacity-60">Szín</div>
                <div class="text-lg font-semibold" id="color">Piros</div>
            </div>

            <div class="p-4 bg-slate-800 rounded-lg border border-slate-700">
                <div class="text-sm opacity-60">Üzemanyag</div>
                <div class="text-lg font-semibold" id="fuel">Benzin</div>
            </div>
        </div>

    </div>

</div>


<!-- ============================================== -->
<!-- CAROUSEL SCRIPT -->
<!-- ============================================== -->

<script>
    // Demo kép URL-ek (ide kerülnek majd a valódi show route-ok)
    const images = [
        "https://picsum.photos/id/237/800/600",
        "https://picsum.photos/id/1015/800/600",
        "https://picsum.photos/id/1025/800/600"
    ];

    let currentIndex = 0;

    function renderCarousel() {
        const main = document.getElementById("mainImage1");
        const thumbs = document.getElementById("thumbs1");

        // Main image
        main.style.opacity = 0;
        setTimeout(() => {
            main.src = images[currentIndex];
            main.style.opacity = 1;
        }, 150);

        // Thumbnails
        thumbs.innerHTML = "";
        images.forEach((url, idx) => {
            const t = document.createElement("img");
            t.src = url;
            t.className =
                "w-12 h-12 rounded-md cursor-pointer opacity-70 hover:opacity-100 border border-slate-700";

            t.onclick = () => {
                currentIndex = idx;
                renderCarousel();
            };

            thumbs.appendChild(t);
        });
    }

    renderCarousel();

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        renderCarousel();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        renderCarousel();
    }
</script>

</body>
</html>
