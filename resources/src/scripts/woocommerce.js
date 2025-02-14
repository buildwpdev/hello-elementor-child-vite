document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ DOM Ready - Event Listeners Attached");

    /**
     * Load Menu Cart Tray via AJAX
     */
    const loadCartTray = async () => {
        console.log("🔄 Loading Cart Tray...");
        try {
            const response = await fetch(EjStoneAjax.ajaxUrl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    action: "load_menu_cart_tray",
                    nonce: EjStoneAjax.nonce,
                }),
            });

            const data = await response.json();
            console.log("✅ AJAX Response:", data);

            if (data.success) {
                document.getElementById("menu-cart-tray-wrapper").innerHTML = data.data.html;
            } else {
                console.error("❌ Failed to load cart tray:", data.data.message);
            }
        } catch (error) {
            console.error("🚨 AJAX Fetch Error:", error);
        }
    };

    /**
     * Centralized Event Listener for All Click Interactions
     */
    document.body.addEventListener("click", (event) => {
        const target = event.target;

        // ✅ Early exit if click is irrelevant
        if (!target) return;

        // 🛑 WooCommerce Dismissable Alerts
        if (target.matches(".woocommerce-message-dismiss")) {
            event.preventDefault();
            const notice = target.closest(".woocommerce-message, .woocommerce-error, .woocommerce-info");
            if (notice) {
                notice.style.transition = "opacity 0.3s ease-out";
                notice.style.opacity = "0";
                setTimeout(() => notice.remove(), 300);
            }
            return;
        }

        // 🛒 WooCommerce Menu Cart Toggle
        if (target.closest(".elementor-menu-cart__toggle_wrapper")) {
            loadCartTray();
            return;
        }

        // ⚡ AJAX Button (For Testing)
        if (target.closest("#elementor-menu-cart__toggle_button")) {
            console.log("🛠️ Test AJAX Button Clicked");
            (async () => {
                try {
                    const response = await fetch(EjStoneAjax.ajaxUrl, {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: new URLSearchParams({
                            action: "load_menu_cart_tray",
                            nonce: EjStoneAjax.nonce,
                            data: "Test Data",
                        }),
                    });

                    const data = await response.json();
                    console.log("✅ Test AJAX Response:", data);
                } catch (error) {
                    console.error("🚨 AJAX Error:", error);
                }
            })();
            return;
        }
    });
});
