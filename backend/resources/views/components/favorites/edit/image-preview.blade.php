<script>
document.getElementById('openFilePicker').addEventListener('click', () => {
    document.getElementById('imageInput').click();
});

document.getElementById('imageInput').addEventListener('change', (event) => {
    const container = document.getElementById('previewContainer');
    container.innerHTML = '';

    Array.from(event.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = "w-28 h-28 object-cover rounded-lg border border-slate-700";
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>