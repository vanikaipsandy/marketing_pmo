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
        Schema::table('mst_initiative_relation', function (Blueprint $table) {
            $table->unsignedInteger('initiative_code_row')->nullable()->change();
            $table->unsignedInteger('initiative_code_column')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trs_initiative_relation', function (Blueprint $table) {
            $table->unsignedInteger('initiative_code_row')->nullable(false)->change();
            $table->unsignedInteger('initiative_code_column')->nullable(false)->change();
        });
    }
};
