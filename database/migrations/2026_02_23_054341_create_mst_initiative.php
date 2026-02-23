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
        Schema::create('mst_initiative', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coe_id');
            $table->unsignedInteger('tipe_initiative')->comment('1: IT Initiative, 2: Digital Initiative');
            $table->unsignedInteger('business_unit');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('coe_id')
                ->references('id')
                ->on('mst_coe')
                ->casecadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('business_unit')
                ->references('id')
                ->on('trs_organization')
                ->casecadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_initiative');
    }
};
