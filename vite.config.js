import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers';
import tailwind from "tailwindcss";
import autoprefixer from "autoprefixer";
import path from 'node:path';
import vueJsx from '@vitejs/plugin-vue-jsx';

export default defineConfig({
    server: {
        cors: {
            origin: '*',
            methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS','PATCH'],
            preflightContinue: false,
            maxAge: 3600,
            allowedHeaders: ['Content-Type', 'Authorization'],
        },
     },
    css: {
        postcss: {
            plugins: [tailwind(), autoprefixer()],
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts','resources/css/app.css'],
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
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver()],
        }),
        vueJsx(),

    ],
    optimizeDeps: {
        exclude: ['js-big-decimal']
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    build: {
        chunkSizeWarningLimit: 1000,
        sourcemap: false,
      },
});
