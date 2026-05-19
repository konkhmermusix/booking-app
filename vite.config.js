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
        // host: "10.116.0.146",
        // cors: true,
        // hmr: {
        //     host: "10.116.0.146",
        // },
        host: "192.168.43.1",
        cors: true,
        hmr: {
            host: "192.168.43.1",
        },
    },
});
