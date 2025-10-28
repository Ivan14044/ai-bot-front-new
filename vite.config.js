import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vueI18n from '@intlify/unplugin-vue-i18n/vite';
import path from 'node:path';

export default defineConfig({
    base: process.env.NODE_ENV === 'production' ? '/ai-bot-front-main/' : '/',
    plugins: [
        vue(),
        vueI18n({
            include: path.resolve(__dirname, './src/locales/**')
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src')
        }
    },
    build: {
        outDir: 'dist',
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor-vue': ['vue', 'vue-router', 'pinia'],
                    'vendor-vuetify': ['vuetify'],
                    'vendor-i18n': ['vue-i18n'],
                    'vendor-utils': ['axios', 'sweetalert2', 'swiper']
                }
            }
        },
        chunkSizeWarningLimit: 1000
    }
});
