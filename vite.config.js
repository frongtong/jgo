import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import laravel from 'laravel-vite-plugin';
import commonjs from 'vite-plugin-commonjs';

export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        array = id.toString().split('node_modules/')[1].split('/');

                        return `vendor/${array[0].toString()}`;
                    }
                },
            },
        },
        chunkSizeWarningLimit: 2000,
    },
    plugins: [
        viteStaticCopy({
            targets: [
                {
                    src: './resources/images' + '/[!.]*',
                    dest: '../dist/images',
                },
                {
                    src: './resources/fonts' + '/[!.]*',
                    dest: '../dist/fonts',
                }
            ],
        }),
        commonjs({
            filter(id) {
                if (id.includes('node_modules/xxx')) {
                    return true
                }
            }
        }),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/ckeditor-classic.js',
            ],
            refresh: true
        }),
    ],
});
