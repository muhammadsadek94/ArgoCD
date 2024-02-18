<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWatchingTypeToWatchedLessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('watched_lessons', function (Blueprint $table) {
            $table->string('subscription_type')->nullable();
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');
        });

        Schema::table('course_enrollment', function (Blueprint $table) {
            $table->string('subscription_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('watched_lessons', function (Blueprint $table) {
            $table->dropColumn('subscription_type');
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
        Schema::table('course_enrollment', function (Blueprint $table) {
            $table->dropColumn('subscription_type');
        });

    }
}
