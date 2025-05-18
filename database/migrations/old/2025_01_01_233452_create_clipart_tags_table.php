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
        Schema::create('clipart_tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('clipart_id')->nullable();
            $table->bigInteger('tag_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clipart_tags');
    }
};
