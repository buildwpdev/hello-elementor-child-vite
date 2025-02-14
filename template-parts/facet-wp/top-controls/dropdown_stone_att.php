<?php

// web/app/themes/build-wp/template-parts/facet-wp/top-controls/dropdown.php

?>

<button 
  data-dropdown-toggle="dropdown-filter-stone" 
  class="bg-stone-200 text-stone-900 hover:bg-stone-300 border border-stone-400 focus:ring-2 focus:outline-none focus:ring-stone-500 font-medium text-sm px-6 py-3 text-center inline-flex items-center dark:bg-stone-800 dark:text-stone-100 dark:hover:bg-stone-700 dark:border-stone-600"
  type="button"
>
    Stone
    <i class="bi bi-chevron-down ms-2"></i> 
</button>

<!-- Dropdown menu -->
<div 
  id="dropdown-filter-stone" 
  class="z-10 hidden bg-stone-100 border border-stone-300 divide-y divide-stone-200 shadow-sm w-56 p-3 dark:bg-stone-800 dark:border-stone-700 dark:divide-stone-600"
>
    <div class="flex flex-col space-y-2">
        <?php echo facetwp_display('facet', 'stone_att'); ?>
    </div>
</div>

