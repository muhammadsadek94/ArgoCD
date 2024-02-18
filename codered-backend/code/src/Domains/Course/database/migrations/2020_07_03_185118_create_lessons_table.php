<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function(Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');

            $table->uuid('chapter_id')->nullable();
            $table->foreign('chapter_id')->references('id')
                ->on('chapters')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->integer('type')->nullable();
            $table->text('overview')->nullable();
            $table->mediumText('video')->nullable();
            $table->string('time')->nullable();
            $table->boolean('activation')->default(0);

            $table->timestamps();
        });

        Schema::create('lesson_upload', function(Blueprint $table) {
            $table->id();

            $table->uuid('lesson_id')->nullable();
            $table->foreign('lesson_id')->references('id')
                ->on('lessons')->onDelete('cascade');

            $table->uuid('attachment_id')->nullable();
            $table->foreign('attachment_id')->references('id')
                ->on('uploads')->onDelete('cascade');

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
        Schema::dropIfExists('lessons');
    }
}
