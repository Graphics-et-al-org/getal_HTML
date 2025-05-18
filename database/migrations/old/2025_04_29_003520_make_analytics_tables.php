<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make tables related to analytics
     */
    public function up(): void
    {
        if (!Schema::hasTable('page_view_analytics')) {
            Schema::create('page_view_analytics', function (Blueprint $table) {
                $table->id();
                // Identify the page
                $table->bigInteger('page_id');
                $table->char('page_uuid', 36)->nullable();
                $table->char('session_id')->nullable();
                $table->char('viewer_id')->nullable();
                $table->bigInteger('visit_number')->nullable();
                $table->char('visit_id')->nullable();
                $table->char('viewer_ip')->nullable();
                $table->boolean('redirect')->nullable();
                // Browser details
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
                $table->id();
                $table->bigInteger('page_view_analytics_id');
                $table->char('event_type')->nullable();
                $table->longText('data')->nullable();
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
        if (Schema::hasTable('page_view_analytics')) {
            Schema::drop('page_view_analytics');
        }

        if (Schema::hasTable('page_view_analytics_events')) {
            Schema::drop('page_view_analytics_events');
        }

    }
};

