import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import laravelTranslator from 'laravel-translator/vite';
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravelTranslator(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler',
            '@sound': path.resolve(__dirname, './resources/sound')
        },
    },
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
        strictPort: true,
    },
});
