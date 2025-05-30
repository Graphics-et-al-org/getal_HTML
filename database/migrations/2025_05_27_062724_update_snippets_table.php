<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('snippets', function (Blueprint $table) {
            $table->text('citation')->nullable();
            $table->text('transcript')->nullable();
            $table->boolean('snippet_template')->nullable();
        });
        Schema::table('snippets_collection', function (Blueprint $table) {
            $table->text('references')->nullable();
        });
    }

    public function down()
    {
        Schema::table('snippets', function (Blueprint $table) {
            $table->dropColumn(['citation', 'transcript', '']);
        });
        Schema::table('snippets_collection', function (Blueprint $table) {
            $table->dropColumn(['references']);
        });
    }
};
