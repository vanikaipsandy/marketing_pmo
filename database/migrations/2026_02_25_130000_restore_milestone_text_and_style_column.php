<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('trs_milestones')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE trs_milestones MODIFY type VARCHAR(255) NOT NULL DEFAULT 'milestone'");
        }

        if (!Schema::hasColumn('trs_milestones', 'milestone_type')) {
            Schema::table('trs_milestones', function (Blueprint $table): void {
                $table->unsignedTinyInteger('milestone_type')->default(1)->after('type');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                UPDATE trs_milestones
                SET milestone_type = CASE
                    WHEN milestone_type BETWEEN 1 AND 5 THEN milestone_type
                    WHEN CAST(type AS UNSIGNED) BETWEEN 1 AND 5 THEN CAST(type AS UNSIGNED)
                    ELSE 1
                END
            ");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('trs_milestones')) {
            return;
        }

        if (Schema::hasColumn('trs_milestones', 'milestone_type')) {
            Schema::table('trs_milestones', function (Blueprint $table): void {
                $table->dropColumn('milestone_type');
            });
        }
    }
};
