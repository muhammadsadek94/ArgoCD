<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableCourseRestructire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('courses', function(Blueprint $table) {
            $table->dropForeign(['syllabus_id']);
            $table->dropColumn('syllabus_id');
            $table->dropColumn('fees');
        });

        Schema::create('course_microdegrees', function(Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');

            $table->uuid('syllabus_id')->nullable();
            $table->foreign('syllabus_id')->references('id')->on('uploads')->onDelete('set null');

            $table->text('prerequisites')->nullable();
            $table->string('average_salary')->nullable();
            $table->string('estimated_time')->nullable();
            $table->decimal('one_time_fees', 9, 3)->nullable();
            $table->decimal('installments_fees', 9, 3)->nullable();

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
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('course_microdegrees');

        Schema::table('courses', function(Blueprint $table) {

            $table->uuid('syllabus_id')->nullable();
            $table->foreign('syllabus_id')->references('id')->on('uploads')->onDelete('set null');


            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

    }
}
