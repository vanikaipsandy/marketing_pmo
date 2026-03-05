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
        if (Schema::hasTable('trs_initiative_relation')) {
            return;
        }

        Schema::create('trs_initiative_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_relasi', 50);
            $table->unsignedBigInteger('initiative_code_row')->nullable();
            $table->unsignedBigInteger('initiative_code_column')->nullable();
            $table->string('type_relation', 100);
            $table->string('description', 500)->nullable();
            $table->string('justifikasi', 500)->nullable();
            $table->timestamps();

            // Foreign key constraints to trs_projects
            $table->foreign('initiative_code_row')
                ->references('id')
                ->on('trs_projects')
                ->nullOnDelete();

            $table->foreign('initiative_code_column')
                ->references('id')
                ->on('trs_projects')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trs_initiative_relation');
    }
};
