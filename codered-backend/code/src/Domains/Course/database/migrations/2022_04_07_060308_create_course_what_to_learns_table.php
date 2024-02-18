<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseWhatToLearnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_what_to_learns', function (Blueprint $table) {
            $table->id();

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('set null');

            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');
            
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
        Schema::dropIfExists('course_what_to_learns');
    }
}
