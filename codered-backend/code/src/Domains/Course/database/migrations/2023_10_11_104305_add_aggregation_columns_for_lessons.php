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
        Schema::table('courses', function (Blueprint $table) {
            $table->json('agg_lessons')->nullable();
            $table->integer('agg_count_course_chapters')->nullable();
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->json('agg_lessons')->nullable();
        });

        Schema::table('learn_path_infos', function (Blueprint $table) {
            $table->json('agg_courses')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('agg_lessons');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('agg_lessons');
        });

        Schema::table('learn_path_infos', function (Blueprint $table) {
            $table->dropColumn('agg_courses');
        });
    }
};
