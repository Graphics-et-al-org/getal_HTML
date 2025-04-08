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
        Schema::table('page_components', function (Blueprint $table) {
            $table->bigInteger('approved_by')->nullable();
            $table->char('approval_status', 36)->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_components', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approval_status', 'approval_notes', 'approved_at']);
        });
    }
};
