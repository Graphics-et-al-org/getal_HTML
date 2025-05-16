<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Page component category
     */
    public function up(): void
    {
        // create the table
        Schema::create('page_component_category', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->text('label');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_component_category');
    }
};
