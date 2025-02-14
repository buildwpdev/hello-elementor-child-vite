document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".tw-select").forEach(selectWrapper => {
        const selectField = selectWrapper.querySelector("select");
        const button = selectWrapper.querySelector(".tw-select-button");
        const dropdown = selectWrapper.querySelector(".tw-select-dropdown");
        const selectedText = selectWrapper.querySelector(".tw-select-selected");
        const chevronIcon = button.querySelector("i");
        const options = JSON.parse(selectWrapper.getAttribute("data-options"));
        const selectedValue = selectWrapper.getAttribute("data-selected");

        let dropdownBuilt = false; // Track if dropdown is built

        // Set initial button text
        selectedText.textContent = options[selectedValue] || "Sort by";

        function toggleDropdown(show) {
            if (show) {
                if (!dropdownBuilt) {
                    buildDropdown(); // Generate options on first hover
                    dropdownBuilt = true;
                }
                dropdown.classList.remove("hidden");
                chevronIcon.classList.replace("bi-chevron-down", "bi-chevron-up");
            } else {
                dropdown.classList.add("hidden");
                chevronIcon.classList.replace("bi-chevron-up", "bi-chevron-down");
            }
        }

        function buildDropdown() {
            dropdown.innerHTML = ""; // Clear dropdown in case of refresh
            Object.entries(options).forEach(([id, name]) => {
                const li = document.createElement("li");
                li.className = "tw-select-option relative cursor-pointer select-none py-2 pl-8 pr-4 text-gray-900 hover:bg-stone-600 hover:text-white";
                li.dataset.value = id;
                li.innerHTML = `
                    <span class="block truncate">${name}</span>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-1.5 text-stone-600 hidden">
                        <i class="bi bi-check2" style="color:#fff !important;"></i>
                    </span>
                `;

                if (id === selectedValue) {
                    li.querySelector("span.absolute").classList.remove("hidden");
                    li.classList.add("bg-stone-600", "text-white"); // Highlight selection
                }

                li.addEventListener("click", function () {
                    selectedText.textContent = name;
                    selectField.value = id;
                    updateSelectedStatus(id);
                    dropdown.classList.add("hidden");
                    selectField.form.submit();
                });

                dropdown.appendChild(li);
            });
        }

        function updateSelectedStatus(selectedId) {
            document.querySelectorAll(".tw-select-option").forEach(option => {
                option.classList.remove("bg-stone-600", "text-white");
                option.querySelector("span.absolute").classList.add("hidden");
                if (option.dataset.value === selectedId) {
                    option.classList.add("bg-stone-600", "text-white");
                    option.querySelector("span.absolute").classList.remove("hidden");
                }
            });
        }

        // Open dropdown on hover and keep it open until the user moves away
        selectWrapper.addEventListener("mouseenter", () => toggleDropdown(true));
        selectWrapper.addEventListener("mouseleave", () => toggleDropdown(false));
    });
});
