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
        Schema::create('trs_digital_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('digital_id');
            $table->string('useCase_description');
            $table->string('current_situation');
            $table->string('key_functionalities');
            $table->string('value_detail');
            $table->string('urgency_detail');
            $table->integer('ease_implementation');
            $table->string('ease_detail');
            $table->integer('resource_requirement');
            $table->string('resource_detail');
            $table->string('interpendencies');
            $table->string('sign_by', 100);
            $table->timestamps();

            $table->foreign('digital_id')
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
        Schema::dropIfExists('trs_digital_detail');
    }
};
