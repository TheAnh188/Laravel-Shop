/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    mode: 'jit',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
            },
            fontFamily: {
                'sf-ui': ['SF UI', 'sans-serif'],
            },
            fontWeight: {
                'thin': 100,
                'ultralight': 200,
                'light': 300,
                'regular': 400,
                'medium': 500,
                'semibold': 600,
                'bold': 700,
                'heavy': 800,
                'black': 900,
            },
        },
    },
    plugins: [],
};
