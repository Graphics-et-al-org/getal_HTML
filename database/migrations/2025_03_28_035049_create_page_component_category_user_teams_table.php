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
        Schema::create('page_component_category_team_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_component_category_id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('team_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_component_category_team_user');
    }
};
