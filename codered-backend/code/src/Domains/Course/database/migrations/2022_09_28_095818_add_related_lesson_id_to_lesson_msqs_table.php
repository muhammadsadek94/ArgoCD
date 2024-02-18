<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedLessonIdToLessonMsqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_msqs', function (Blueprint $table) {
            $table->uuid('related_lesson_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_msqs', function (Blueprint $table) {
            $table->dropColumn('related_lesson_id');
        });
    }
}
