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
        Schema::table('trs_review_pc', function (Blueprint $table) {
            $table->text('detail_kesimpulan')->change();
            $table->text('penjelasan')->change();
            $table->text('why')->change();
            $table->text('how')->change();
            $table->text('project_profile')->change();
            $table->text('key_milestone')->change();
            $table->text('risk_impact')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trs_review_pc', function (Blueprint $table) {
            $table->string('penjelasan', 255)->change();
            $table->string('why', 255)->change();
            $table->string('how', 255)->change();
            $table->string('project_profile', 255)->change();
            $table->string('key_milestone', 255)->change();
            $table->string('risk_impact', 255)->change();
        });
    }
};
