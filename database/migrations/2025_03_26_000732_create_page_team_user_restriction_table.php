<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_team_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id')->nullable();
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
        Schema::dropIfExists('page_team_user');
    }
};
