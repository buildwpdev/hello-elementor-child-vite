<?php

// web/app/themes/ej-stone-co/template-parts/facet-wp/top-controls/dropdown.php


?>


<button 
  data-dropdown-toggle="dropdown" 
  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" 
  type="button"
>
  <i class="bi bi-chevron-down ms-2"></i> 
</button>

<!-- Dropdown menu -->
<div 
  id="dropdown" 
  class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700"
>
<?php echo facetwp_display('facet', 'shop_by_price');?>
</div>
