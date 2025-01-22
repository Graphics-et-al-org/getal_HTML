import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import sass from "vite-plugin-sass";

export default defineConfig({
    plugins: [
        sass(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/frontend/frontend.js",
                "resources/js/backend/backend.js",
                "resources/css/backend/backend.css",
                "resources/js/backend/template_builder/builder.js",
                "resources/js/backend/template_builder/blocks.js",
                "resources/css/backend/template_builder/builder.css",
                "resources/js/backend/clipart/create.js",
                "resources/js/backend/clipart/edit.js",
                "resources/js/backend/user/create.js",
                "resources/js/backend/user/edit.js",
                "resources/js/backend/team/members.js",
            ],
            refresh: true,
        }),
    ],
});
