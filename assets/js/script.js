
function confirmDelete(message) {
    return confirm(message || "Are you sure you want to delete this item?");
}


document.addEventListener("DOMContentLoaded", function () {
    const heightInput = document.getElementById("height");
    const weightInput = document.getElementById("weight");
    const preview = document.getElementById("live-bmi-preview");

    if (heightInput && weightInput && preview) {
        function updatePreview() {
            const h = parseFloat(heightInput.value) / 100; // cm to m
            const w = parseFloat(weightInput.value);
            if (h > 0 && w > 0) {
                const bmi = (w / (h * h)).toFixed(1);
                preview.textContent = "Live preview: " + bmi;
            } else {
                preview.textContent = "";
            }
        }
        heightInput.addEventListener("input", updatePreview);
        weightInput.addEventListener("input", updatePreview);
    }

  
    const symptomForm = document.getElementById("symptom-form");
    if (symptomForm) {
        symptomForm.addEventListener("submit", function (e) {
            const checked = symptomForm.querySelectorAll("input[type=checkbox]:checked");
            const extraField = document.getElementById("extra_symptoms");
            const hasText = extraField && extraField.value.trim().length > 0;
            if (checked.length === 0 && !hasText) {
                e.preventDefault();
                alert("Please select at least one symptom or describe how you feel.");
            }
        });
    }
});
