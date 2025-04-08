import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import sass from "vite-plugin-sass";
import path from 'path';

export default defineConfig({
    plugins: [
        sass(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/backend/backend.css",
                "resources/css/public.css",
                "resources/css/backend/template_builder/builder.css",
                "resources/js/app.js",
                "resources/js/common/tailwind_classes.js",
                "resources/js/frontend/frontend.js",
                "resources/js/backend/backend.js",
                "resources/js/backend/template/index.js",
                "resources/js/backend/template_builder/builder.js",
                "resources/js/backend/template_builder/builder_tinymce.js",
                "resources/js/backend/template_builder/blocks.js",
                "resources/js/backend/page_components/index.js",
                "resources/js/backend/page_components/builder.js",
                "resources/js/backend/page_components/builder_tinymce.js",
                'resources/js/backend/page_component_category/index.js',
                'resources/js/backend/page_component_category/edit.js',
                'resources/js/backend/projects/index.js',
                'resources/js/backend/projects/edit.js',
                "resources/js/backend/clipart/index.js",
                "resources/js/backend/clipart/create.js",
                "resources/js/backend/clipart/edit.js",
                "resources/js/backend/user/create.js",
                "resources/js/backend/user/edit.js",
                "resources/js/backend/team/members.js",
                "resources/js/frontend/clinician_page/clinician_page.js",
            ],
            resolve: {
                alias: {
                    'tinymce': path.resolve(__dirname, 'node_modules/tinymce')
                }
            },
            refresh: true,
        }),
    ],
});
