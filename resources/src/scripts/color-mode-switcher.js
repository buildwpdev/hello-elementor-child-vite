// resources/src/scripts/color-mode-switcher.js

document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.querySelector("#btn-toggle-color-mode");
    if (!toggleButton) return;

    const htmlElement = document.documentElement;
    const storageKey = "color-mode";

    // Get stored mode from localStorage or Cookie
    let mode = localStorage.getItem(storageKey) || document.cookie.split('; ').find(row => row.startsWith("color-mode="))?.split('=')[1] || "light";

    // Apply mode on page load
    htmlElement.classList.toggle("dark", mode === "dark");

    // Toggle mode when button is clicked
    toggleButton.addEventListener("click", () => {
        const isDark = htmlElement.classList.toggle("dark");

        // Store in localStorage
        localStorage.setItem(storageKey, isDark ? "dark" : "light");

        // Save to cookie (for PHP access)
        document.cookie = `color-mode=${isDark ? "dark" : "light"}; path=/; max-age=31536000`; // 1 year
    });
});
