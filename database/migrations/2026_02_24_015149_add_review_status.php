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
        Schema::table('trs_pc_status_implementation', function (Blueprint $table) {
            $table->enum('review_status', ['At Risk', 'On Track', 'Not Started', 'Not Signed'])->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trs_pc_status_implementation', function (Blueprint $table) {
            $table->dropColumn('review_status');
        });
    }
};
