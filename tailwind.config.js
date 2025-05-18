import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {

        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                NRT: ['NRT', ...defaultTheme.fontFamily.sans],
                droidkufi: ['DroidKufi', ...defaultTheme.fontFamily.sans],
                Sarchia_Qaisy_bold: ['Sarchia_Qaisy_bold', ...defaultTheme.fontFamily.sans],

            },
            screens: {
                '2xsm': '375px',
                xsm: '425px',
                '3xl': '2000px',
                ...defaultTheme.screens,
            },
            colors: {
                primary: '#409eff',
                secondary: '#80CAEE',
                success: '#219653',
                danger: '#D34053',
                warning: '#FFA70B',
            },
        },
    },

    plugins: [forms, typography],
};
