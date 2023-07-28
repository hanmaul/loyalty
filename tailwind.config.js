const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/Components/**/*.vue',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        colors: {
            'sky': '#0369a1',
            'kamm-blue':'#3498db',
            'kamm-soft-blue':'#8cc2e6',
            'kamm-red':'#8f3232'
        },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('flowbite/plugin')
    ],
};
