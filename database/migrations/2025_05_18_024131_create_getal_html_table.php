<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //dd('Running migration');
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('clipart')) {
            Schema::create('clipart', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->softDeletes();
                $table->bigInteger('owner_id');
                $table->string('name');
                $table->string('type');
                $table->boolean('preferred')->nullable();
                $table->boolean('fallback')->nullable();
                $table->text('preferred_description')->nullable();
                $table->text('fallback_description')->nullable();
                $table->text('gpt4_description')->nullable();
                $table->text('bert_text_embedding_b64')->nullable();
                $table->text('clip_image_embedding_b64')->nullable();
                $table->bigInteger('created_by')->nullable();
                $table->binary('thumb')->nullable();
                $table->text('description')->nullable();
                $table->boolean('sensitive');
            });
        }

        if (!Schema::hasTable('clipart_colourways')) {
            Schema::create('clipart_colourways', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->softDeletes();
                $table->bigInteger('clipart_id');
                $table->bigInteger('colour_id');
                $table->longText('data');
                $table->char('uuid', 36);
            });
        }

        if (!Schema::hasTable('clipart_colourways_colour')) {
            Schema::create('clipart_colourways_colour', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->string('name');
                $table->string('colour_code');
            });
        }

        if (!Schema::hasTable('clipart_tags')) {
            Schema::create('clipart_tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->bigInteger('clipart_id')->nullable();
                $table->bigInteger('tag_id')->nullable();
            });
        }

        if (!Schema::hasTable('compiled_page_components')) {
            Schema::create('compiled_page_components', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('from_page_template_components_id');
                $table->integer('order')->default(0);
                $table->timestamps();
                $table->bigInteger('compiled_page_id')->nullable();
                $table->longText('content')->nullable();
                $table->string('type', 45)->nullable();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('compiled_page_snippets')) {
            Schema::create('compiled_page_snippets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->char('uuid', 36);
                $table->char('type');
                $table->longText('content');
                $table->bigInteger('compiled_page_components_id');
                $table->bigInteger('from_template_id');
                $table->bigInteger('order');
                $table->softDeletes();
                $table->timestamps();
                $table->bigInteger('paired_image_id')->nullable();
            });
        }

        if (!Schema::hasTable('compiled_pages')) {
            Schema::create('compiled_pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('from_template_id')->nullable();
                $table->char('uuid', 36);
                $table->bigInteger('user_id');
                $table->bigInteger('group_id')->nullable();
                $table->text('label')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->string('job_uuid')->nullable();
                $table->timestamp('released_at')->nullable();
                $table->longText('header')->nullable();
                $table->longText('footer')->nullable();
                $table->longText('css')->nullable();
                $table->bigInteger('project_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->longText('data')->nullable();
                $table->longText('title')->nullable();
                $table->longText('summary')->nullable();
                $table->text('target_uuid')->nullable();
            });
        }

        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }

        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->char('uuid', 36);
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->string('type')->nullable();
                $table->string('path')->nullable();
                $table->string('location')->nullable();
                $table->char('size', 45)->nullable();
                $table->binary('thumb')->nullable();
                $table->bigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('media_team_user')) {
            Schema::create('media_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('media_id')->nullable();
                $table->bigInteger('project_id')->nullable();
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_tags')) {
            Schema::create('page_tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->bigInteger('page_id')->nullable();
                $table->bigInteger('tag_id')->nullable();
            });
        }

        if (!Schema::hasTable('page_team_user')) {
            Schema::create('page_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('page_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_components')) {
            Schema::create('page_template_components', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->char('uuid', 36);
                $table->bigInteger('user_id')->nullable();
                $table->text('label')->nullable();
                $table->text('description')->nullable();
                $table->char('type');
                $table->longText('content')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_components_projects')) {
            Schema::create('page_template_components_projects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('project_id');
                $table->bigInteger('page_template_component_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_components_tags')) {
            Schema::create('page_template_components_tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('tag_id');
                $table->bigInteger('page_template_component_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_components_team_user')) {
            Schema::create('page_template_components_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('page_template_component_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_components_templates')) {
            Schema::create('page_template_components_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('template_id');
                $table->bigInteger('page_template_components_id');
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_projects')) {
            Schema::create('page_template_projects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('project_id');
                $table->bigInteger('page_template_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_tags')) {
            Schema::create('page_template_tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('tag_id');
                $table->bigInteger('page_template_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_template_team_user')) {
            Schema::create('page_template_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('page_template_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_templates')) {
            Schema::create('page_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->char('uuid', 36);
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

        if (!Schema::hasTable('page_view_analytics')) {
            Schema::create('page_view_analytics', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('page_id');
                $table->char('page_uuid', 36)->nullable();
                $table->char('session_id')->nullable();
                $table->char('viewer_id')->nullable();
                $table->bigInteger('visit_number')->nullable();
                $table->char('visit_id')->nullable();
                $table->char('viewer_ip')->nullable();
                $table->boolean('redirect')->nullable();
                $table->text('browser_name')->nullable();
                $table->text('browser_version')->nullable();
                $table->text('platform')->nullable();
                $table->char('page_load_time')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_view_analytics_events')) {
            Schema::create('page_view_analytics_events', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('page_view_analytics_id');
                $table->char('event_type')->nullable();
                $table->longText('data')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id')->index('permission_role_role_id_foreign');

                $table->primary(['permission_id', 'role_id']);
            });
        }

        if (!Schema::hasTable('permission_user')) {
            Schema::create('permission_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('permission_id')->index('permission_user_permission_id_foreign');
                $table->unsignedBigInteger('user_id');
                $table->string('user_type');
                $table->unsignedBigInteger('team_id')->nullable()->index('permission_user_team_id_foreign');

                $table->unique(['user_id', 'permission_id', 'user_type', 'team_id']);
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('tokenable_type');
                $table->unsignedBigInteger('tokenable_id');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index(['tokenable_type', 'tokenable_id']);
            });
        }

        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('label');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('projects_pages')) {
            Schema::create('projects_pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('project_id');
                $table->bigInteger('page_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('projects_team_user')) {
            Schema::create('projects_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('project_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('queue_monitor')) {
            Schema::create('queue_monitor', function (Blueprint $table) {
                $table->increments('id');
                $table->char('job_uuid', 36)->nullable();
                $table->string('job_id')->index();
                $table->string('name')->nullable();
                $table->string('queue')->nullable();
                $table->unsignedInteger('status')->default(0);
                $table->dateTime('queued_at')->nullable();
                $table->timestamp('started_at')->nullable()->index();
                $table->string('started_at_exact')->nullable();
                $table->timestamp('finished_at')->nullable();
                $table->string('finished_at_exact')->nullable();
                $table->integer('attempt')->default(0);
                $table->boolean('retried')->default(false);
                $table->integer('progress')->nullable();
                $table->longText('exception')->nullable();
                $table->text('exception_message')->nullable();
                $table->text('exception_class')->nullable();
                $table->longText('data')->nullable();
            });
        }

        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('role_id')->index('role_user_role_id_foreign');
                $table->unsignedBigInteger('user_id');
                $table->string('user_type');
                $table->unsignedBigInteger('team_id')->nullable()->index('role_user_team_id_foreign');

                $table->unique(['user_id', 'role_id', 'user_type', 'team_id']);
            });
        }

        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
                $table->string('default')->nullable();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        if (!Schema::hasTable('snippets')) {
            Schema::create('snippets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->char('uuid', 36);
                $table->bigInteger('user_id');
                $table->bigInteger('team_id')->nullable();
                $table->text('label')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->longText('html')->nullable();
                $table->longText('css')->nullable();
                $table->binary('thumb')->nullable();
                $table->bigInteger('weight')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->boolean('keypoint')->nullable();
                $table->text('data')->nullable();
                $table->bigInteger('approved_by')->nullable();
                $table->char('approval_status', 36)->nullable();
                $table->text('approval_notes')->nullable();
                $table->timestamp('approved_at')->nullable();
            });
        }

        if (!Schema::hasTable('snippets_collection')) {
            Schema::create('snippets_collection', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->char('uuid', 36)->nullable();
                $table->text('label');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('snippets_collection_projects')) {
            Schema::create('snippets_collection_projects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('snippet_collection_id');
                $table->bigInteger('project_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('snippets_collection_snippets')) {
            Schema::create('snippets_collection_snippets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('snippet_id');
                $table->bigInteger('snippet_collection_id');
                $table->bigInteger('order')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('snippets_collection_snippets_team_user')) {
            Schema::create('snippets_collection_snippets_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('snippet_collection_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('snippets_projects')) {
            Schema::create('snippets_projects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('snippet_id');
                $table->bigInteger('project_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('snippets_tags')) {
            Schema::create('snippets_tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->bigInteger('snippet_id')->nullable();
                $table->bigInteger('tag_id')->nullable();
            });
        }

        if (!Schema::hasTable('snippets_team_user')) {
            Schema::create('snippets_team_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('snippet_id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('snippets_use_tracker')) {
            Schema::create('snippets_use_tracker', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('snippet_id');
                $table->bigInteger('page_id');
                $table->timestamps();
                $table->text('action')->nullable();
                $table->text('old_value')->nullable();
                $table->text('new_value')->nullable();
            });
        }

        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->text('text');
                $table->string('primary', 10)->nullable();
            });
        }

        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->string('provider')->nullable();
                $table->string('provider_id')->nullable();
                $table->string('avatar')->nullable();
            });
        }

        if (Schema::hasTable('permission_role')) {
            Schema::table('permission_role', function (Blueprint $table) {
                $table->foreign(['permission_id'])->references(['id'])->on('permissions')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['role_id'])->references(['id'])->on('roles')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('permission_user')) {
            Schema::table('permission_user', function (Blueprint $table) {
                $table->foreign(['permission_id'])->references(['id'])->on('permissions')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['team_id'])->references(['id'])->on('teams')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('role_user')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->foreign(['role_id'])->references(['id'])->on('roles')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign(['team_id'])->references(['id'])->on('teams')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('role_user')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->dropForeign('role_user_role_id_foreign');
                $table->dropForeign('role_user_team_id_foreign');
            });
        }

        if (Schema::hasTable('permission_user')) {
            Schema::table('permission_user', function (Blueprint $table) {
                $table->dropForeign('permission_user_permission_id_foreign');
                $table->dropForeign('permission_user_team_id_foreign');
            });
        }

        if (Schema::hasTable('permission_role')) {
            Schema::table('permission_role', function (Blueprint $table) {
                $table->dropForeign('permission_role_permission_id_foreign');
                $table->dropForeign('permission_role_role_id_foreign');
            });
        }

        Schema::dropIfExists('users');

        Schema::dropIfExists('teams');

        Schema::dropIfExists('tags');

        Schema::dropIfExists('snippets_use_tracker');

        Schema::dropIfExists('snippets_team_user');

        Schema::dropIfExists('snippets_tags');

        Schema::dropIfExists('snippets_projects');

        Schema::dropIfExists('snippets_collection_snippets_team_user');

        Schema::dropIfExists('snippets_collection_snippets');

        Schema::dropIfExists('snippets_collection_projects');

        Schema::dropIfExists('snippets_collection');

        Schema::dropIfExists('snippets');

        Schema::dropIfExists('sessions');

        Schema::dropIfExists('roles');

        Schema::dropIfExists('role_user');

        Schema::dropIfExists('queue_monitor');

        Schema::dropIfExists('projects_team_user');

        Schema::dropIfExists('projects_pages');

        Schema::dropIfExists('projects');

        Schema::dropIfExists('personal_access_tokens');

        Schema::dropIfExists('permissions');

        Schema::dropIfExists('permission_user');

        Schema::dropIfExists('permission_role');

        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('page_view_analytics_events');

        Schema::dropIfExists('page_view_analytics');

        Schema::dropIfExists('page_templates');

        Schema::dropIfExists('page_template_team_user');

        Schema::dropIfExists('page_template_tags');

        Schema::dropIfExists('page_template_projects');

        Schema::dropIfExists('page_template_components_templates');

        Schema::dropIfExists('page_template_components_team_user');

        Schema::dropIfExists('page_template_components_tags');

        Schema::dropIfExists('page_template_components_projects');

        Schema::dropIfExists('page_template_components');

        Schema::dropIfExists('page_team_user');

        Schema::dropIfExists('page_tags');

        Schema::dropIfExists('media_team_user');

        Schema::dropIfExists('media');

        Schema::dropIfExists('jobs');

        Schema::dropIfExists('job_batches');

        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('compiled_pages');

        Schema::dropIfExists('compiled_page_snippets');

        Schema::dropIfExists('compiled_page_components');

        Schema::dropIfExists('clipart_tags');

        Schema::dropIfExists('clipart_colourways_colour');

        Schema::dropIfExists('clipart_colourways');

        Schema::dropIfExists('clipart');

        Schema::dropIfExists('cache_locks');

        Schema::dropIfExists('cache');
    }
};
