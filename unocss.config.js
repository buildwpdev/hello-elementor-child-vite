// unocss.config.js

// import { defineConfig, presetUno, presetIcons } from 'unocss'
import presetMini from '@unocss/preset-mini'
import { defineConfig } from 'unocss'

export default defineConfig({
    presets: [
        presetMini(),
    ],
    scan: {
        dirs: ['.'], // Scan all files in the theme directory
        fileExtensions: ['php', 'html', 'js', 'css'], // Include PHP files
    }
})
