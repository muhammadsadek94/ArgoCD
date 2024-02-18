<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchHistoryTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watch_history_times', function (Blueprint $table) {
            $table->id();
            $table->uuid('instructor_id')->nullable();
            $table->foreign('instructor_id')->references('id')
                ->on('users')->onDelete('cascade');


            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');


            $table->uuid('lesson_id')->nullable();
            $table->foreign('lesson_id')->references('id')
                ->on('lessons')->onDelete('cascade');


            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');

            $table->string('subscription_type')->nullable();

            $table->string('course_type')->nullable();

            $table->string('watched_time')->nullable();

            $table->boolean('status')->nullable();//to show is paid or not

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
        Schema::dropIfExists('watch_history_times');
    }
}
