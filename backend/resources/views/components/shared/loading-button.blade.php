@props(['text' => 'Mentés'])

<button
    type="submit"
    class="loading-submit w-full bg-accent text-black px-6 py-3 rounded-lg font-semibold hover:bg-green-400 transition flex items-center justify-center"
>
    <span class="loading-spinner hidden animate-spin mr-2 border-2 border-black border-t-transparent rounded-full w-4 h-4"></span>
    <span class="button-text">{{ $text }}</span>
</button>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Minden loading-submit gombra működik
    document.querySelectorAll(".loading-submit").forEach(button => {
        const spinner = button.querySelector(".loading-spinner");
        const text = button.querySelector(".button-text");

        // Form submit esemény – ez a biztos
        const form = button.closest("form");
        if (!form) return;

        form.addEventListener("submit", () => {
            // Dupla kattintás tiltása
            button.disabled = true;
            button.classList.add("opacity-60", "cursor-not-allowed");

            // Spinner megjelenítése
            spinner.classList.remove("hidden");

            // Ha biztosra akarunk menni:
            spinner.style.display = "inline-block";

            // Szöveg halványítása
            text.style.opacity = "0.5";
        });
    });
});
</script>
