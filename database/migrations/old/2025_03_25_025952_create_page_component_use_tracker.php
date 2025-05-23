<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create a table that logs when a component is used in a page
     */
    public function up(): void
    {
        Schema::create('page_component_use_tracker', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('component_id');
            $table->bigInteger('page_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_component_use_tracker');
    }
};
