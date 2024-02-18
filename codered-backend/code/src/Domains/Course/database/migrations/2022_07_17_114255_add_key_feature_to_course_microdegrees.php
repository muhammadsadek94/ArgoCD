<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyFeatureToCourseMicrodegrees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_microdegrees', function (Blueprint $table) {
            $table->json('key_features')->nullable();
            $table->json('skills_covered')->nullable();
            $table->json('project')->nullable();
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
            $table->dropColumn('key_features');
            $table->dropColumn('skills_covered');
            $table->dropColumn('project');
        });
    }
}
