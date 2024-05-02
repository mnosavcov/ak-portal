import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Spartan-Regular, Nunito', ...defaultTheme.fontFamily.sans],
                'Spartan-ExtraLight': ['Spartan-ExtraLight', ...defaultTheme.fontFamily.sans],
                'Spartan-Bold': ['Spartan-Bold', ...defaultTheme.fontFamily.sans],
                'Spartan-Black': ['Spartan-Black', ...defaultTheme.fontFamily.sans],
                'Spartan-ExtraBold': ['Spartan-ExtraBold', ...defaultTheme.fontFamily.sans],
                'Spartan-Thin': ['Spartan-Thin', ...defaultTheme.fontFamily.sans],
                'Spartan-SemiBold': ['Spartan-SemiBold', ...defaultTheme.fontFamily.sans],
                'Spartan-Medium': ['Spartan-Medium', ...defaultTheme.fontFamily.sans],
                'Spartan-Light': ['Spartan-Light', ...defaultTheme.fontFamily.sans],
                'Spartan-Regular': ['Spartan-Regular', ...defaultTheme.fontFamily.sans],
                'Spartan-Spartan': ['Spartan-Spartan', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
