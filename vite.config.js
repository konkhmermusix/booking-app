import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },

        // cors: true,
        // strictPort: true,
        // hmr: {
        //     host: "stock-drank-headache.ngrok-free.dev", // ដាក់លីង ngrok របស់បង
        //     protocol: "wss",
        //     clientPort: 443,
        // },

        // host: "0.0.0.0",
        // cors: true,
        // hmr: {
        //     host: "localhost",
        // },
        // host: "10.116.0.17",
        // cors: true,
        // hmr: {
        //     host: "10.116.0.17",
        // },
        // host: "192.168.43.1",
        // cors: true,
        // hmr: {
        //     host: "192.168.43.1",
        // },
    },
});
