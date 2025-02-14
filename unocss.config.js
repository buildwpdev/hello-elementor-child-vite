// unocss.config.js

import { defineConfig, presetUno, presetIcons } from 'unocss'

export default defineConfig({
    presets: [
        presetUno(),
        presetIcons({ scale: 1.2 })
    ],
    scan: {
        dirs: ['.'], // Scan all files in the theme directory
        fileExtensions: ['php', 'html', 'js', 'css'], // Include PHP files
    }
})
