/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'selector',
    content: [
        '../Partials/**/*.html',
        '../Templates/**/*.html',
    ],
    theme: {
        extend: {
            animation: {
                'float': 'float 3s ease-in-out infinite',
                'pulse-slow': 'pulse 5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                }
            },
            colors: {
                'wtl-red-dark': {
                    DEFAULT: '#AA0033',
                    50: '#FF6392',
                    100: '#FF4E83',
                    200: '#FF2567',
                    300: '#FC004B',
                    400: '#D3003F',
                    500: '#AA0033',
                    600: '#720022',
                    700: '#3A0011',
                    800: '#020001',
                    900: '#000000',
                },
                'wtl-red': {
                    DEFAULT: '#CC0033',
                    50: '#FF85A3',
                    100: '#FF7094',
                    200: '#FF4775',
                    300: '#FF1F57',
                    400: '#F5003D',
                    500: '#CC0033',
                    600: '#940025',
                    700: '#5C0017',
                    800: '#240009',
                    900: '#000000',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
    corePlugins: {
        preflight: false,
    },
}

