<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**Create a new structure for templating and page compilation from snippets */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the page templates table

        if (!Schema::hasTable('page_templates')) {
            Schema::create('page_templates', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->bigInteger('user_id')->nullable();
                $table->text('label')->nullable();
                $table->text('description')->nullable();
                $table->longText('header')->nullable();
                $table->longText('footer')->nullable();
                $table->longText('css')->nullable();
                $table->char('template_type')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // link the page template components to users and teams for restricted access
        if (!Schema::hasTable('page_template_team_user')) {
            Schema::create('page_template_team_user', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('page_template_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }


        // // Create the page template components table
        if (!Schema::hasTable('page_template_components')) {
            Schema::create('page_template_components', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->bigInteger('user_id')->nullable();
                $table->text('label')->nullable();
                $table->text('description')->nullable();
                $table->char('type');
                $table->longText('content');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // // link the page template components to users and teams for restricted access
        if (!Schema::hasTable('page_template_components_team_user')) {
        Schema::create('page_template_components_team_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_template_component_id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('team_id')->nullable();
            $table->timestamps();
        });
    }

        // // link the page template components to the page templates
        if (!Schema::hasTable('page_template_components_templates')) {
            Schema::create('page_template_components_templates', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('template_id');
                $table->bigInteger('page_template_components_id');
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        // // Build a page from components
        if (!Schema::hasTable('page_components_in_page')) {
            Schema::create('page_components_in_page', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('template_id');
                $table->bigInteger('page_template_components_id');
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        // // update teh page table
        Schema::table('page', function (Blueprint $table) {
            if (Schema::hasColumn('page', 'is_template')) {
                $table->dropColumn('is_template');
            }
            // $table->dropColumn('is_template');
            if (!Schema::hasColumn('page', 'header')) {
                $table->longText('header')->nullable();
            }
            if (!Schema::hasColumn('page', 'header')) {
            $table->longText('footer');
            }
            if (Schema::hasColumn('page', 'css')) {
            $table->dropColumn('css');
            }
            if (!Schema::hasColumn('page', 'template_id')) {
            $table->bigInteger('template_id')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        if (Schema::hasTable('page_templates')) {
            Schema::drop('page_templates');
        }
        // Create the page templates table
        if (Schema::hasTable('page_templates')) {
            Schema::drop('page_templates');
        }

        // Create the page template components table
        if (Schema::hasTable('page_template_components')) {
            Schema::drop('page_template_components');
        }

        // link the page template components to the page templates
        if (Schema::hasTable('page_template_components_templates')) {
            Schema::drop('page_template_components_templates');
        }

        // Build a page from components
        if (Schema::hasTable('page_components_in_page')) {
            Schema::drop('page_components_in_page');
        }

        if (Schema::hasTable('page_template_team_user')) {
            Schema::drop('page_template_team_user');
        }

        Schema::table('page', function (Blueprint $table) {
            if (Schema::hasColumn('page', 'is_template')) {
                $table->dropColumn('is_template');
            }
            // $table->dropColumn('is_template');
            if (Schema::hasColumn('page', 'header')) {
                $table->dropColumn('header');
            }
            if (Schema::hasColumn('page', 'footer')) {
                $table->dropColumn('footer');
            }
            if (Schema::hasColumn('page', 'css')) {
                $table->dropColumn('css');
            }
            if (Schema::hasColumn('page', 'template_id')) {
                $table->dropColumn('template_id');
            }
        });
    }
};
