import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        minify: "terser",
        terserOptions: {
            compress: {
                passes: 2,
                drop_console: true,
            },
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    lodash: ["lodash"],
                    moment: ["moment"],
                    swal: ["sweetalert2"],
                    yup: ["yup"],
                    pdfmake: ["pdfmake", "pdfmake/build/vfs_fonts"],
                    jquery: ["jquery"],
                    axios: ["axios"],
                },
            },
        },
        chunkSizeWarningLimit: 1000,
    },
});
