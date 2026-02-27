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
        Schema::create('trs_pc_initiative', function (Blueprint $table) {
            $table->unsignedBigInteger('pc_id');
            $table->unsignedInteger('initiative_id');
            $table->primary(['pc_id', 'initiative_id']);
            $table->timestamps();

            $table->foreign('pc_id')
                ->references('id')
                ->on('trs_projects')
                ->casecadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('initiative_id')
                ->references('id')
                ->on('mst_initiative')
                ->casecadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trs_pc_initiative');
    }
};
