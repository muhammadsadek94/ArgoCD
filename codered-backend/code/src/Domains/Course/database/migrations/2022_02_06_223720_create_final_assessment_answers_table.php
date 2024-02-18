<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalAssessmentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_assessment_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');

            $table->uuid('assessment_id')->nullable();
            $table->foreign('assessment_id')->references('id')
                ->on('course_assessments')->onDelete('cascade');

            $table->uuid('assessment_answer_id')->nullable();
            $table->foreign('assessment_answer_id')->references('id')
                ->on('course_assessments_answers')->onDelete('cascade');

            $table->boolean('activation')->default(1);

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
        Schema::dropIfExists('final_assessment_answers');
    }
}
