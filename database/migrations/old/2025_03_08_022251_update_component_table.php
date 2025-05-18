<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('page_components', function (Blueprint $table) {
            $table->boolean('keypoint')->nullable();

        });
    }

    public function down()
    {
        Schema::table('page_components', function (Blueprint $table) {
            $table->dropColumn(['keypoint']);
        });
    }
};
