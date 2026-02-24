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
        if (Schema::hasTable('mst_initiative_relation')) {
            return;
        }

        Schema::create('mst_initiative_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_relasi', 50);
            $table->unsignedInteger('initiative_code_row');
            $table->unsignedInteger('initiative_code_column');
            $table->string('type_relation', 100);
            $table->string('description', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_initiative_relation');
    }
};
