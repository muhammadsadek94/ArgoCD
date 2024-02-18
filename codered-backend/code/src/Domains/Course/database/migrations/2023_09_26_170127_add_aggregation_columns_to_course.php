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
            $table->float('agg_avg_reviews')->nullable();
            $table->integer('agg_count_reviews')->nullable();
            $table->integer('agg_count_course_enrollment')->nullable();
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
            $table->dropColumn('agg_avg_reviews');
            $table->dropColumn('agg_count_reviews');
            $table->dropColumn('agg_count_course_enrollment');
        });
    }
};
