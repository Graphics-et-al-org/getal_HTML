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
        Schema::create('clipart', function (Blueprint $table) {
            $table->id();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clipart');
    }
};
