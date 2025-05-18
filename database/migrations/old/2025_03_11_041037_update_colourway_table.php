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
        Schema::table('clipart_colourways', function (Blueprint $table) {
            $table->uuid();

        });
    }

    public function down()
    {
        Schema::table('clipart_colourways', function (Blueprint $table) {
            $table->dropColumn(['uuid']);
        });
    }
};
