<form class="woocommerce-ordering" method="get">
    <div class="tw-select relative">
        <!-- Hidden Select Field -->
        <select name="orderby" class="sr-only">
            <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>>
                    <?php echo esc_html($name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Custom Tailwind Dropdown Button -->
        <button type="button" class="tw-select-button grid w-full cursor-pointer grid-cols-1 rounded-md bg-white py-1.5 pl-3 pr-2 text-left text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
            <span class="tw-select-selected col-start-1 row-start-1 truncate pr-6">
                <?php echo esc_html($catalog_orderby_options[$orderby] ?? 'Sort by'); ?>
            </span>
            <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Custom Dropdown Options -->
        <ul class="tw-select-dropdown absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm hidden">
            <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                <li class="tw-select-option relative cursor-pointer select-none py-2 pl-8 pr-4 text-gray-900 hover:bg-indigo-600 hover:text-white"
                    data-value="<?php echo esc_attr($id); ?>">
                    <span class="block truncate"><?php echo esc_html($name); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <input type="hidden" name="paged" value="1" />
    <?php wc_query_string_form_fields(null, array('orderby', 'submit', 'paged', 'product-page')); ?>
</form>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".tw-select").forEach(selectWrapper => {
        const selectField = selectWrapper.querySelector("select");
        const customBtn = selectWrapper.querySelector(".tw-select-button");
        const dropdown = selectWrapper.querySelector(".tw-select-dropdown");
        const selectedText = selectWrapper.querySelector(".tw-select-selected");
        const options = selectWrapper.querySelectorAll(".tw-select-option");

        // Toggle dropdown visibility
        customBtn.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent event bubbling
            dropdown.classList.toggle("hidden");
        });

        // Select an option, update select field, and submit form
        options.forEach(option => {
            option.addEventListener("mousedown", function (event) {
                event.preventDefault(); // Prevent focus loss issues
                const value = this.getAttribute("data-value");
                const text = this.innerText;

                // Update button text
                selectedText.innerText = text;

                // Update hidden select field
                selectField.value = value;

                // Close dropdown
                dropdown.classList.add("hidden");

                // Submit the form
                selectField.form.submit();
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (!selectWrapper.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });

        // Close dropdown when losing focus (for accessibility)
        selectWrapper.addEventListener("focusout", function () {
            setTimeout(() => dropdown.classList.add("hidden"), 150);
        });
    });
});
</script>