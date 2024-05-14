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
                'WorkSans-Black': ['WorkSans-Black', ...defaultTheme.fontFamily.sans],
                'WorkSans-BlackItalic': ['WorkSans-BlackItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-Bold': ['WorkSans-Bold', ...defaultTheme.fontFamily.sans],
                'WorkSans-BoldItalic': ['WorkSans-BoldItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-ExtraBold': ['WorkSans-ExtraBold', ...defaultTheme.fontFamily.sans],
                'WorkSans-ExtraBoldItalic': ['WorkSans-ExtraBoldItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-ExtraLight': ['WorkSans-ExtraLight', ...defaultTheme.fontFamily.sans],
                'WorkSans-Italic': ['WorkSans-Italic', ...defaultTheme.fontFamily.sans],
                'WorkSans-ExtraLightItalic': ['WorkSans-ExtraLightItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-LightItalic': ['WorkSans-LightItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-Light': ['WorkSans-Light', ...defaultTheme.fontFamily.sans],
                'WorkSans-Medium': ['WorkSans-Medium', ...defaultTheme.fontFamily.sans],
                'WorkSans-Regular': ['WorkSans-Regular', ...defaultTheme.fontFamily.sans],
                'WorkSans-SemiBold': ['WorkSans-SemiBold', ...defaultTheme.fontFamily.sans],
                'WorkSans-MediumItalic': ['WorkSans-MediumItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-ThinItalic': ['WorkSans-ThinItalic', ...defaultTheme.fontFamily.sans],
                'WorkSans-Thin': ['WorkSans-Thin', ...defaultTheme.fontFamily.sans],
                'WorkSans-SemiBoldItalic': ['WorkSans-SemiBoldItalic', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                app: {
                    DEFAULT: '#414141',
                    green: {
                        DEFAULT: '#7ec447',
                    },
                    blue: {
                        DEFAULT: '#0376c8',
                    },
                    red: {
                        DEFAULT: '#f54848',
                    },
                    orange: {
                        DEFAULT: '#f2940d',
                    }
                }
            },
            screens: {
                'mobile': '480px',
                // => @media (min-width: 480px) { ... }

                'tablet': '640px',
                // => @media (min-width: 640px) { ... }

                'laptop': '1024px',
                // => @media (min-width: 1024px) { ... }

                'desktop': '1280px',
                // => @media (min-width: 1280px) { ... }
            },
        },
    },

    plugins: [forms],
};
