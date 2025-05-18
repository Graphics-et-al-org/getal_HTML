<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('page', function (Blueprint $table) {
            $table->string('job_uuid')->nullable();

        });
    }

    public function down()
    {
        Schema::table('page', function (Blueprint $table) {
            $table->dropColumn(['job_uuid']);
        });
    }
};
