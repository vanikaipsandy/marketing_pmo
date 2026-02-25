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
        Schema::table('trs_sc_initiative', function (Blueprint $table) {
            $table->unsignedInteger('useCase_id')->nullable()->change();
            $table->dropColumn('useCase_description');

            $table->foreign('useCase_id')
                ->references('id')
                ->on('mst_initiative')
                ->casecadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trs_sc_initiative', function (Blueprint $table) {
            $table->dropForeign(['useCase_id']);
            $table->string('useCase_description')->nullable();
            $table->unsignedInteger('useCase_id')->nullable(false)->change();
        });
    }
};
