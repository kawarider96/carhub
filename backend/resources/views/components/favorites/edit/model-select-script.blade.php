<script>
    const modelsByBrand = @json($modelsByBrand);

    const brandSelect = document.getElementById('brand');
    const modelSelect = document.getElementById('car_model_id');

    function populateModelSelectEdit(brandId, selectedModelId) {

        // NINCS "Válassz..." opció! TÖK ÜRESRE TÖRÖLJÜK
        modelSelect.innerHTML = "";

        if (modelsByBrand[brandId]) {
            modelsByBrand[brandId].forEach(model => {

                const option = document.createElement('option');
                option.value = model.id;
                option.textContent = model.name;

                // EZ AZ EGYETLEN DOLOG, AMI KELL:
                if (model.id == selectedModelId) {
                    option.selected = true;
                }

                modelSelect.appendChild(option);
            });

            modelSelect.removeAttribute('disabled');
        }
    }

    // EDIT oldal → initial load
    document.addEventListener('DOMContentLoaded', () => {

        // Ezek az EDIT-ben biztosan léteznek
        const selectedBrandId = "{{ $favoriteCar->carModel->car_brand_id }}";
        const selectedModelId = "{{ $favoriteCar->car_model_id }}";

        // állítsd be BRANDET
        brandSelect.value = selectedBrandId;

        // TÖLTSD BE A MODELL LISTÁT az adott brandhez
        populateModelSelectEdit(selectedBrandId, selectedModelId);
    });

    // Ha edit közben brandet változtat a user:
    brandSelect.addEventListener('change', () => {
        populateModelSelectEdit(brandSelect.value, null);
    });
</script>
