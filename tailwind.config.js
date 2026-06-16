/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            fontFamily: {
                kantumruy: ["Kantumruy Pro", "sans-serif"],
            },
        },
    },
    plugins: [require("@tailwindcss/typography")],
};
