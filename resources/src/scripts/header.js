document.addEventListener("DOMContentLoaded", () => {


    //alert('WEE');
    
    document.querySelectorAll(".toggle-button").forEach(button => {
        button.addEventListener("click", () => {
            console.log('✅ Button clicked');
            button.classList.toggle("active");

            // Get the data-action attribute value
            const action = button.getAttribute("data-action");

            // Call the corresponding function if it exists
            if (action && typeof window[action] === "function") {
                window[action](button); // Pass the button element in case needed
            } else {
                console.warn(`Function ${action} not found`);
            }
        });
    });
});

// ✅ Assign function explicitly to `window`
window.toggleMainHeader = function (button) {
    const headerMain = document.getElementById("header-main");
    const headerTop = document.getElementById("header-top");
    const mainMenu = document.getElementById("main-menu");

    if (!headerMain || !headerTop || !mainMenu) {
        console.warn("⚠️ #header-main, #header-top, or #main-menu not found");
        return;
    }

    // Toggle the 'open' class based on the button state
    const isOpen = button.classList.contains("active");
    headerMain.classList.toggle("open", isOpen);
    headerTop.classList.toggle("open", isOpen);

    if (isOpen) {
        console.log("📌 Menu is open, moving #main-menu back to original position");
        restoreMainMenu();
    } else {
        console.log("❌ Menu closed.");
    }

    console.log(`🔥 Header is ${isOpen ? "opened" : "closed"}`);
};

/**
 * Sticky effect handling
 */
document.addEventListener("DOMContentLoaded", () => {
    const headerMain = document.querySelector("#header-main");
    const headerTop = document.querySelector("#header-top");
    const mainMenu = document.querySelector("#main-menu");
    const topMenuContainer = document.querySelector("#top-menu-container .e-con-inner");

    if (!headerMain || !headerTop || !mainMenu || !topMenuContainer) {
        console.warn("⚠️ Missing required elements: #header-main, #header-top, #main-menu, or #top-menu-container.");
        return;
    }

    // Store the original parent and position of #main-menu
    const originalParent = mainMenu.parentNode;
    const originalNextSibling = mainMenu.nextSibling;

    function restoreMainMenu() {
        if (originalNextSibling) {
            originalParent.insertBefore(mainMenu, originalNextSibling);
        } else {
            originalParent.appendChild(mainMenu);
        }
    }

    // Function to check for the presence of the class
    function handleClassChange(mutations) {
        mutations.forEach(mutation => {
            if (mutation.attributeName === "class") {
                const isStickyEffectActive = headerMain.classList.contains("elementor-sticky--effects");
                const isOpen = headerMain.classList.contains("open");

                if (isStickyEffectActive && !isOpen) {
                    //console.log("📌 Moving #main-menu to #top-menu-container (first position)");
                    topMenuContainer.insertBefore(mainMenu, topMenuContainer.firstChild);
                } else {
                    //console.log("❌ Restoring #main-menu to its original position");
                    restoreMainMenu();
                }
            }
        });
    }

    // Set up MutationObserver to detect class changes on #header-main
    const observer = new MutationObserver(handleClassChange);
    observer.observe(headerMain, { attributes: true, attributeFilter: ["class"] });
});
