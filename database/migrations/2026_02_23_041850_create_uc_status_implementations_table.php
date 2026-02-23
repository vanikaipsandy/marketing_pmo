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
        Schema::create('trs_uc_status_implementation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_initiative_id')->constrained('mst_digitalInitiative')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->date('date')->nullable();
            $table->time('time_start')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trs_uc_status_implementation');
    }
};
