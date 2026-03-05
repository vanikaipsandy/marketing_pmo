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
        Schema::table('trs_digital_initiative', function (Blueprint $table) {
            $table->string('useCase_description')->after('useCase_id');
            $table->unsignedInteger('category_fase')->after('id')
            ->comment('1: planning / scope charter, 2: implementation / project charter');
            $table->unsignedInteger('ref_id')->nullable()->after('category_fase');      
            
            $table->foreign('ref_id')
                ->references('id')
                ->on('trs_digital_initiative')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trs_digital_initiative', function (Blueprint $table) {
            $table->string('useCase_description')->after('useCase_id');
        });
    }
};
