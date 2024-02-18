<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_knowledge_assessment_tags', function (Blueprint $table) {
            $table->id();
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');

            $table->uuid('chapter_id')->nullable();
            $table->foreign('chapter_id')->references('id')
                ->on('chapters')->onDelete('cascade');

            $table->uuid('speciality_area_id')->nullable();
            $table->foreign('speciality_area_id')->references('id')
                ->on('specialty_areas')->onDelete('cascade');

            $table->uuid('competency_id')->nullable();
            $table->foreign('competency_id')->references('id')
                ->on('competencies')->onDelete('cascade');

            $table->uuid('ksa_id')->nullable();
            $table->foreign('ksa_id')->references('id')
                ->on('ksas')->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_knowledge_assessment_tags');
    }
};
