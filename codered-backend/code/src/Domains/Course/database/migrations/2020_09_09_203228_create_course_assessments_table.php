<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
            ->on('courses')->onDelete('cascade');


            $table->text('question')->nullable();

            $table->timestamps();
        });

        Schema::create('course_assessments_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_assessments_id')->nullable();
            $table->foreign('course_assessments_id')->references('id')
            ->on('course_assessments')->onDelete('cascade');

            $table->text('answer')->nullable();
            $table->boolean('is_correct')->nullable();

            $table->timestamps();
        });

        Schema::table('course_assessments', function (Blueprint $table) {
            $table->uuid('correct_answer_id')->nullable();
            $table->foreign('correct_answer_id')->references('id')
                ->on('course_assessments_answers')->onDelete('set null');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_assessments_answers');
        Schema::dropIfExists('course_assessments');
    }
}
