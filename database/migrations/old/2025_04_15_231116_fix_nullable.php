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
        Schema::table('page_template_components', function (Blueprint $table) {
            if (Schema::hasColumn('page_template_components', 'content')) {
                $table->longText('content')->nullable()->change();
            }
        });

        if (!Schema::hasTable('snippets_tags')) {
            Schema::create('snippets_tags', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('snippet_id');
                $table->bigInteger('tag_id');
                $table->timestamps();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
