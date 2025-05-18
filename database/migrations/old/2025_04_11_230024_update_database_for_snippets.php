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
        // rename tables to clarify snippets

        // rename the page_components table to snippets
        if (Schema::hasTable('page_components')) {
            Schema::rename('page_components', 'snippets');
        }


        Schema::table('snippets', function (Blueprint $table) {
            if (Schema::hasColumn('snippets', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });

        if (Schema::hasTable('page_component_projects')) {
            Schema::rename('page_component_projects', 'snippets_projects');
        }

        Schema::table('snippets_projects', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_projects', 'page_component_id')) {
                $table->renameColumn('page_component_id', 'snippet_id');
            }
        });

        if (Schema::hasTable('page_component_team_user')) {
            Schema::rename('page_component_team_user', 'snippets_team_user');
        }

        Schema::table('snippets_team_user', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_team_user', 'page_component_id')) {
                $table->renameColumn('page_component_id', 'snippet_id');
            }
        });

        if (Schema::hasTable('page_component_use_tracker')) {
            Schema::rename('page_component_use_tracker', 'snippets_use_tracker');
        }

        Schema::table('snippets_use_tracker', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_use_tracker', 'component_id')) {
                $table->renameColumn('component_id', 'snippet_id');
            }
        });

        // rename the page_component_category table to snippets_category
        if (Schema::hasTable('page_component_category')) {
            Schema::rename('page_component_category', 'snippets_category');
        }

        if (Schema::hasTable('page_component_category_components')) {
            Schema::rename('page_component_category_components', 'snippets_category_snippets');
        }

        Schema::table('snippets_category_snippets', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_category_snippets', 'page_component_id')) {
                $table->renameColumn('page_component_id', 'snippet_id');
            }
            if (Schema::hasColumn('snippets_category_snippets', 'page_component_category_id')) {
                $table->renameColumn('page_component_category_id', 'snippet_category_id');
            }
        });

        if (Schema::hasTable('snippets_category_snippets_projects')) {
            Schema::rename('snippets_category_snippets_projects', 'snippets_category_projects');
        }


        Schema::table('snippets_category_projects', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_category_projects', 'project_id')) {
                $table->renameColumn('project_id', 'snippet_id');
            }
            if (Schema::hasColumn('snippets_category_projects', 'page_component_category_id')) {
                $table->renameColumn('page_component_category_id', 'snippet_category_id');
            }
        });

        if (Schema::hasTable('page_component_category_team_user')) {
            Schema::rename('page_component_category_team_user', 'snippets_category_snippets_team_user');
        }

        Schema::table('snippets_category_snippets_team_user', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_category_snippets_team_user', 'page_component_category_id')) {
                $table->renameColumn('page_component_category_id', 'snippet_category_id');
            }
        });

        if (Schema::hasTable('static_component_static_component_tags')) {
            Schema::rename('static_component_tags', 'snippets_tags');
        }

        Schema::table('snippets_tags', function (Blueprint $table) {
            if (Schema::hasColumn('snippets_tags', 'static_component_id')) {
                $table->renameColumn('static_component_id', 'snippet_id');
            }
        });

        Schema::table('snippets_use_tracker', function (Blueprint $table) {
            //  $table->renameColumn('page_component_id', 'snippet_id');
            if (!Schema::hasColumn('snippets_use_tracker', 'action')) {
                $table->text('action')->nullable();
            }
            if (!Schema::hasColumn('snippets_use_tracker', 'old_value')) {
                $table->text('old_value')->nullable();
            }
            if (!Schema::hasColumn('snippets_use_tracker', 'new_value')) {
                $table->text('new_value')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
