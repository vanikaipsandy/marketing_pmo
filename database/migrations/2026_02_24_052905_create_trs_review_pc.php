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
        Schema::create('trs_review_pc', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('initiative_id');
            $table->string('kesimpulan', 255);
            $table->string('detail_kesimpulan', 255);
            $table->string('penjelasan', 255);
            $table->string('why', 255);
            $table->string('what', 255);
            $table->string('how', 255);
            $table->string('project_profile', 255);
            $table->string('key_milestone', 255);
            $table->string('risk_impact', 255);
            $table->timestamps();

            $table->foreign('initiative_id')
                ->references('id')
                ->on('mst_initiative')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trs_review_pc');
    }
};
