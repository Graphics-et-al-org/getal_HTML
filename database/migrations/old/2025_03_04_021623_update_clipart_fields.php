<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// update to add a 'description' field
return new class extends Migration
{
    public function up()
    {
        Schema::table('clipart', function (Blueprint $table) {
            $table->text('description')->nullable();

        });
    }

    public function down()
    {
        Schema::table('clipart', function (Blueprint $table) {
            $table->dropColumn(['description']);
        });
    }
};
