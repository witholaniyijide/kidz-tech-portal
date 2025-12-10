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
                // Using system font stack for better performance and reliability
                sans: [
                    'ui-sans-serif',
                    'system-ui',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Roboto',
                    '"Helvetica Neue"',
                    'Arial',
                    '"Noto Sans"',
                    'sans-serif',
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ],
            },
            colors: {
                brand: {
                    blue: '#3B82F6',
                    indigo: '#6366F1',
                    violet: '#8B5CF6',
                    pink: '#EC4899'
                },
                role: {
                    director: {
                        start: '#3B82F6',
                        end: '#6366F1'
                    },
                    admin: {
                        start: '#14B8A6',
                        end: '#06B6D4'
                    },
                    manager: {
                        start: '#0ea5e9',
                        end: '#38bdf8'
                    },
                    tutor: {
                        start: '#8B5CF6',
                        end: '#EC4899'
                    },
                    parent: {
                        start: '#0ea5e9',
                        end: '#22d3ee'
                    }
                }
            },
            backgroundImage: {
                'gradient-director': 'linear-gradient(to right, #3B82F6, #6366F1)',
                'gradient-admin': 'linear-gradient(to right, #14B8A6, #06B6D4)',
                'gradient-manager': 'linear-gradient(to right, #0ea5e9, #38bdf8)',
                'gradient-tutor': 'linear-gradient(to right, #8B5CF6, #EC4899)',
                'gradient-parent': 'linear-gradient(to right, #0ea5e9, #22d3ee)',
            }
        },
    },

    plugins: [forms],
};
