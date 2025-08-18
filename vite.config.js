import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import glob from 'fast-glob';

export default defineConfig({
    plugins: [
        laravel({
            input: glob.sync([
                'resources/**/*.js',
                'resources/**/*.css'
            ]),
            refresh: true,
        }),
        tailwindcss(),
    ],
    define: {
        'import.meta.env.VITE_REVERB_APP_KEY': JSON.stringify(process.env.REVERB_APP_KEY),
        'import.meta.env.VITE_REVERB_HOST': JSON.stringify(process.env.REVERB_HOST),
        'import.meta.env.VITE_REVERB_PORT': JSON.stringify(process.env.REVERB_PORT),
        'import.meta.env.VITE_REVERB_SCHEME': JSON.stringify(process.env.REVERB_SCHEME),
    },
});
