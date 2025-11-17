<script>
    const modelsByBrand = @json($modelsByBrand);
    const brandSelect   = document.getElementById('brand');
    const modelSelect   = document.getElementById('car_model_id');

    function populateModelSelect(brandId, selectedModelId = null) {
        modelSelect.innerHTML = '<option value="">Válassz...</option>';

        if (modelsByBrand[brandId]) {
            modelsByBrand[brandId].forEach(model => {
                const option = document.createElement('option');
                option.value = model.id;
                option.textContent = model.name;

                if (selectedModelId && selectedModelId == model.id) {
                    option.selected = true;
                }

                modelSelect.appendChild(option);
            });

            modelSelect.removeAttribute('disabled');
        } else {
            modelSelect.setAttribute('disabled', 'disabled');
        }
    }

    brandSelect.addEventListener('change', () => {
        populateModelSelect(brandSelect.value);
    });

    window.addEventListener('DOMContentLoaded', () => {
        const oldBrand = brandSelect.value;
        const oldModel = "{{ old('car_model_id') }}";

        if (oldBrand) {
            populateModelSelect(oldBrand, oldModel);
        }
    });
</script>
