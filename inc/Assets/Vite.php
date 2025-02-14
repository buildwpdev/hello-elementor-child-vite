<?php

// wp-content/themes/ej-stone-co/wp/Assets/Vite.php

namespace EjStoneCo\WpChildTheme\Assets;

class Vite {
    protected static $manifest = null;

    /**
     * Check if the Vite development server is running.
     */
    public static function isDevServerRunning(): bool {
        $viteUrl = 'http://localhost:5173/@vite/client';

        $ch = curl_init($viteUrl);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 200;
    }


    /**
     * Load and cache the Vite manifest file.
     */
    public static function getManifest(): array {
        if (self::$manifest !== null) {
            return self::$manifest;
        }

        $manifestPath = get_theme_file_path('resources/dist/.vite/manifest.json');

        if (!file_exists($manifestPath)) {
            // error_log("Vite manifest.json not found.");
            return [];
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!$manifest) {
            // error_log("Failed to decode manifest.json");
            return [];
        }

        self::$manifest = $manifest;
        return $manifest;
    }


    /**
     * Load and parse the Vite manifest file.
     */
    public static function loadManifest() {
        if (self::$manifest !== null) {
            return self::$manifest;
        }

        $manifestPath = get_theme_file_path('resources/dist/.vite/manifest.json');

        if (!file_exists($manifestPath)) {
            // error_log("Vite manifest.json not found.");
            return [];
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!$manifest) {
            // error_log("Failed to decode manifest.json");
            return [];
        }

        self::$manifest = $manifest;
        return $manifest;
    }

    /**
     * Get the correct asset URL from the Vite manifest.
     */
    public static function asset(string $file): string {
        $manifest = self::loadManifest();

        if (!isset($manifest[$file])) {
            // error_log("Vite asset missing: " . $file);
            return '';
        }

        $entry = $manifest[$file];

        if (isset($entry['file'])) {
            return get_theme_file_uri('resources/dist/' . $entry['file']);
        }

        if (isset($entry['css'][0])) {
            return get_theme_file_uri('resources/dist/' . $entry['css'][0]);
        }

        // error_log("Vite asset missing file or CSS: " . $file);
        return '';
    }
}
