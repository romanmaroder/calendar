import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';
import tailwindcss from "@tailwindcss/vite";
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from '@primevue/auto-import-resolver';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    isCustomElement: (tag) => ['DialogDescription'].includes(tag),
                }
            },
        }),
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        })
    ],
    server: {
        host: '0.0.0.0', // слушать все интерфейсы
        port: 5173,
        strictPort: true,
        cors: true, // включить CORS
        hmr: {
            host: '192.168.0.21', // IP вашего сервера
            protocol: 'http',
            clientPort: 5173
        }
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
});
