<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_static_components', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->bigInteger('user_id');
            $table->bigInteger('team_id')->nullable();
            $table->text('label')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->binary('thumb')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_static_components');
    }
};
