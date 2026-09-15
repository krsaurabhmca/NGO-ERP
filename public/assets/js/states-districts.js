/**
 * Populate district dropdown based on selected state
 * @param {HTMLSelectElement} stateSelect - The state <select> element
 * @param {string} districtSelectId - ID of the district <select> element
 */
function populateDistricts(stateSelect, districtSelectId) {
    const districtSelect = document.getElementById(districtSelectId);
    const selectedState = stateSelect.value;
    const currentValue = districtSelect.value;

    districtSelect.innerHTML = '<option value="">Select District</option>';
    districtSelect.disabled = !selectedState;

    if (selectedState && typeof STATES_DISTRICTS !== 'undefined' && STATES_DISTRICTS[selectedState]) {
        STATES_DISTRICTS[selectedState].forEach(function(district) {
            const opt = document.createElement('option');
            opt.value = district;
            opt.textContent = district;
            districtSelect.appendChild(opt);
        });
        // Re-select previous value if still valid
        if (currentValue && STATES_DISTRICTS[selectedState].includes(currentValue)) {
            districtSelect.value = currentValue;
        }
    }
}

// Auto-attach on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-state-district]').forEach(function(stateSelect) {
        const districtId = stateSelect.getAttribute('data-district-id');
        if (districtId) {
            stateSelect.addEventListener('change', function() {
                populateDistricts(this, districtId);
            });
            // If a state is already selected (e.g. edit form), populate districts
            if (stateSelect.value) {
                populateDistricts(stateSelect, districtId);
            }
        }
    });
});
