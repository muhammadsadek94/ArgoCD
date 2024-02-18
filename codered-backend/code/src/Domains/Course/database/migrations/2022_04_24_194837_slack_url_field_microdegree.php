<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SlackUrlFieldMicrodegree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_microdegrees',  function (Blueprint $table) {
            $table->string('slack_url', 1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_microdegrees',  function (Blueprint $table) {
            $table->dropColumn('slack_url');
        });
    }
}
