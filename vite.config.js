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
                //"resources/css/backend/template_builder/builder.css",
                "resources/js/app.js",
                "resources/js/common/tailwind_classes.js",
                "resources/js/frontend/frontend.js",
                "resources/js/backend/backend.js",
                "resources/js/backend/pages_templates/index.js",
                "resources/js/backend/pages_templates/builder_tinymce.js",
                "resources/js/backend/template_components/index.js",
                "resources/js/backend/template_components/builder_tinymce.js",
                "resources/js/backend/snippets/index.js",
                "resources/js/backend/snippets/builder_tinymce.js",
                'resources/js/backend/snippets_category/index.js',
                'resources/js/backend/snippets_category/edit.js',
                'resources/js/backend/project/create.js',
                'resources/js/backend/project/edit.js',
                "resources/js/backend/clipart/index.js",
                "resources/js/backend/clipart/create.js",
                "resources/js/backend/clipart/edit.js",
                "resources/js/backend/user/create.js",
                "resources/js/backend/user/edit.js",
                "resources/js/backend/team/members.js",
                "resources/js/frontend/clinician_page/clinician_page.js",
                "resources/js/frontend/share_view/share_view.js",
                "resources/js/public/public_view.js",
                "resources/js/public/public_analytics.js",
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
