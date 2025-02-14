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
//import '@/scripts/facetwp-front.js';





const testAjaxMethod = async () => {
    console.log("🔄 Triggering Test AJAX Request:", EjStoneAjax.ajaxUrl);

    try {
        const response = await fetch(EjStoneAjax.ajaxUrl, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                action: "test_ajax_method",
                nonce: EjStoneAjax.nonce,
            }),
        });

        const text = await response.text();
        console.log("📜 RAW RESPONSE:", text);

        try {
            const data = JSON.parse(text);
            console.log("✅ Test AJAX Response:", data);
        } catch (jsonError) {
            console.error("🚨 JSON Parse Error:", jsonError);
        }
    } catch (fetchError) {
        console.error("🚨 AJAX Fetch Error:", fetchError);
    }
};

// ✅ Expose the function globally for testing
window.testAjaxMethod = testAjaxMethod;






// ✅ HMR support without extra functions
if (import.meta.hot) {
    import.meta.hot.accept(() => {
        console.log('♻️ HMR Reloaded');
    });
}
