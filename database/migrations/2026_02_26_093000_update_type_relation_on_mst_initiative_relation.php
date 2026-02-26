<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('mst_initiative_relation')
            ->where('type_relation', 'TBC')
            ->update(['type_relation' => 1]);

        Schema::table('mst_initiative_relation', function (Blueprint $table) {
            $table->unsignedInteger('type_relation')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_initiative_relation', function (Blueprint $table) {
            $table->string('type_relation', 100)->change();
        });
    }
};
