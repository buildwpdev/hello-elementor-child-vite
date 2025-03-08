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

