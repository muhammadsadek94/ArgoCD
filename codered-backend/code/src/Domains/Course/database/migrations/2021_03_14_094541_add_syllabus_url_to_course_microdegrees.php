<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSyllabusUrlToCourseMicrodegrees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_microdegrees', function (Blueprint $table) {
            $table->dropForeign(['syllabus_id']);
            $table->dropColumn('syllabus_id');
        });

        Schema::table('course_microdegrees', function (Blueprint $table) {
            $table->string('syllabus_url')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_microdegrees', function (Blueprint $table) {
            $table->dropColumn('syllabus_url');
        });

        Schema::table('course_microdegrees', function (Blueprint $table) {
            $table->uuid('syllabus_id')->nullable();
            $table->foreign('syllabus_id')->references('id')->on('uploads')->onDelete('set null');
        });
    }
}
