<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // rename snippets_category_snippets
        if (Schema::hasTable('snippets_category')) {
            Schema::rename('snippets_category', 'snippets_collection');
        }

        if (Schema::hasTable('snippets_category_snippets')) {
            Schema::rename('snippets_category_snippets', 'snippets_collection_snippets');
        }

        Schema::table('snippets_collection_snippets', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_collection_snippets', 'snippet_category_id')) {
                $table->renameColumn('snippet_category_id', 'snippet_collection_id');
            }
        });

        if (Schema::hasTable('snippets_category_projects')) {
            Schema::rename('snippets_category_projects', 'snippets_collection_projects');
        }
        Schema::table('snippets_collection_projects', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_collection_projects', 'snippet_category_id')) {
                $table->renameColumn('snippet_category_id', 'snippet_collection_id');
            }
            if (Schema::hasColumn('snippets_collection_projects', 'snippet_id')) {
                $table->renameColumn('snippet_id', 'project_id');
            }
        });

        if (Schema::hasTable('snippets_category_snippets_team_user')) {
            Schema::rename('snippets_category_snippets_team_user', 'snippets_collection_snippets_team_user');
        }

        Schema::table('snippets_collection_snippets_team_user', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_collection_snippets_team_user', 'snippet_category_id')) {
                $table->renameColumn('snippet_category_id', 'snippet_collection_id');
            }
        });

        // add target_uuid to compiled_pages to track the target audience
        Schema::table('compiled_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('compiled_pages', 'target_uuid')) {
                $table->text('target_uuid')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // if (Schema::hasTable('snippets_collection_snippets')) {
        //     Schema::rename('snippets_collection_snippets', 'snippets_category_snippets');
        // }
        // Schema::table('snippets_category_snippets', function (Blueprint $table) {
        //     if (Schema::hasColumn('snippets_category_snippets', 'snippet_category_id')) {
        //         $table->renameColumn('snippet_collection_id', 'snippet_category_id');
        //     }
        // });

        // if (Schema::hasTable('snippets_collection_projects')) {
        //     Schema::rename('snippets_category_projects', 'snippets_collection_projects');
        // }


        // add target_uuid to compiled_pages to track the target audience
        Schema::table('compiled_pages', function (Blueprint $table) {
            if (Schema::hasColumn('compiled_pages', 'target_uuid')) {
                $table->removeColumn('target_uuid');
            }
        });
    }
};
