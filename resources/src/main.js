// wp-content/themes/build-wp/resources/src/main.js

// import 'bootstrap-icons/font/bootstrap-icons.css';

//import '@/vendor/bootstrap-icons/bootstrap-icons.min.css';
//import "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css";

import 'flowbite';
import '@/styles/main.scss';
import '@/scripts/header.js';
import '@/scripts/woocommerce.js';
import '@/scripts/fields-forms.js';
import '@/scripts/color-mode-switcher.js';
//import '@/scripts/facetwp-front.js';·










// ✅ HMR support without extra functions
if (import.meta.hot) {
    import.meta.hot.accept(() => {
        console.log('♻️ HMR Reloaded');
    });
}
