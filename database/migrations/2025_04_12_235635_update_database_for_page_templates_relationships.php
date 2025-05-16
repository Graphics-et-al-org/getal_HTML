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
        Schema::create('page_template_components_projects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->bigInteger('page_template_component_id');
            $table->timestamps();
        });

        Schema::create('page_template_components_tags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tag_id');
            $table->bigInteger('page_template_component_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_template_components_projects');
        Schema::dropIfExists('page_template_components_tags');
    }
};
