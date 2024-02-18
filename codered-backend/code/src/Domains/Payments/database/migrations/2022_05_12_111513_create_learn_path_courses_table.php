<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnPathCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_path_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('learn_path_id')->nullable();
            $table->foreign('learn_path_id')->references('id')
                ->on('learn_path_infos')->onDelete('set null');

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('set null');

            $table->string('weight')->nullable();
            $table->integer('sort')->nullable();

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
        Schema::dropIfExists('learn_path_courses');
    }
}
