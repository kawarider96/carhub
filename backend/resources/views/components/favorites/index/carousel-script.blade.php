<script>
const currentImages = {};
const currentIndex = {};

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-carousel-id]").forEach(wrapper => {
        const id = wrapper.getAttribute("data-carousel-id");

        const thumbs = wrapper.querySelectorAll("[data-thumb]");
        currentImages[id] = [...thumbs].map(t => t.src);

        currentIndex[id] = 0;
    });
});

function updateImage(id) {
    const img = document.querySelector(`[data-main='${id}']`);
    if (!img) return;

    img.classList.add("opacity-0");

    setTimeout(() => {
        img.src = currentImages[id][currentIndex[id]];
        img.classList.remove("opacity-0");
    }, 150);
}

function nextImage(id) {
    const total = currentImages[id]?.length || 0;
    if (total < 2) return;

    currentIndex[id] = (currentIndex[id] + 1) % total;
    updateImage(id);
}

function prevImage(id) {
    const total = currentImages[id]?.length || 0;
    if (total < 2) return;

    currentIndex[id] = (currentIndex[id] - 1 + total) % total;
    updateImage(id);
}

function setImage(id, url) {
    const idx = currentImages[id].indexOf(url);
    if (idx === -1) return;

    currentIndex[id] = idx;
    updateImage(id);
}
</script>
