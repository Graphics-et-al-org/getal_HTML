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

        if (Schema::hasTable('page')) {
            Schema::rename('page', 'compiled_pages');
        }


        Schema::table('compiled_pages', function (Blueprint $table) {
            if (Schema::hasColumn('compiled_pages', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('compiled_pages', 'html')) {
                $table->dropColumn('html');
            }
            if (Schema::hasColumn('compiled_pages', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('compiled_pages', 'template_id')) {
                $table->dropColumn('template_id');
            }

            if (Schema::hasColumn('compiled_pages', 'template_type')) {
                $table->dropColumn('template_type');
            }
            if (!Schema::hasColumn('compiled_pages', 'css')) {
                $table->longText('css')->nullable();;
            }
            if (!Schema::hasColumn('compiled_pages', 'header')) {
                $table->longText('header')->nullable();
            }
            if (!Schema::hasColumn('compiled_pages', 'footer')) {
                $table->longText('footer')->nullable();
            }

            if (!Schema::hasColumn('compiled_pages', 'project_id')) {
                $table->bigInteger('project_id')->nullable();
            }

            if (!Schema::hasColumn('compiled_pages', 'team_id')) {
                $table->bigInteger('team_id')->nullable();
            }
            if (!Schema::hasColumn('compiled_pages', 'data')) {
                $table->longText('data')->nullable();
            }
            if (!Schema::hasColumn('compiled_pages', 'title')) {
                $table->longText('title')->nullable();
            }
            if (!Schema::hasColumn('compiled_pages', 'summary')) {
                $table->longText('summary')->nullable();
            }
        });



        if (Schema::hasTable('page_components_in_page')) {
            Schema::rename('page_components_in_page', 'compiled_page_components');
        }

        Schema::table('compiled_page_components', function (Blueprint $table) {
            if (Schema::hasColumn('compiled_page_components', 'template_id')) {
                $table->dropColumn('template_id');
            }
            if (Schema::hasColumn('compiled_page_components', 'page_template_components_id')) {
                $table->renameColumn('page_template_components_id', 'from_page_template_components_id');
            }

            if (!Schema::hasColumn('compiled_page_components', 'compiled_page_id')) {
                $table->bigInteger('compiled_page_id');
            }

            if (!Schema::hasColumn('compiled_page_components', 'content')) {
                $table->bigInteger('content')->nullable();
            }

            if (!Schema::hasColumn('compiled_page_components', 'type')) {
                $table->char('type', 45)->nullable();
            }


            if (!Schema::hasColumn('compiled_page_components', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            }
        });


        if (!Schema::hasTable('compiled_page_snippets')) {
            Schema::create('compiled_page_snippets', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->char('type');
                $table->longText('content');
                $table->bigInteger('compiled_page_components_id');
                $table->bigInteger('from_template_id');
                $table->bigInteger('order');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('compiled_page_snippets');

        if (Schema::hasTable('compiled_pages')) {
            Schema::rename('compiled_pages', 'page');
        }
    }
};
