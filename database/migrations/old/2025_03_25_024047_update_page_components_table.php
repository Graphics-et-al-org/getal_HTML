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
        // rename the table
        if(Schema::hasTable('page_static_components')) {
           Schema::rename('page_static_components', 'page_components');
        }

        // add some other fields
        // update the page component category
        Schema::table('page_components', function (Blueprint $table) {
            $table->bigInteger('category_id')->nullable();
            $table->text('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('page_components', 'page_static_components');
    }
};
