<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('trs_digital_initiative')) {
            Schema::rename('trs_digital_initiative', 'trs_sc_initiative');
        }

        if (Schema::hasTable('trs_digital_detail')) {
            Schema::rename('trs_digital_detail', 'trs_sc_detail');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('trs_sc_initiative')) {
            Schema::rename('trs_sc_initiative', 'trs_digital_initiative');
        }

        if (Schema::hasTable('trs_sc_detail')) {
            Schema::rename('trs_sc_detail', 'trs_digital_detail');
        }
    }
};
