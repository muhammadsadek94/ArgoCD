<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvgSalaryToLearnPathInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learn_path_infos', function (Blueprint $table) {
            $table->string('avg_salary')->nullable();
            $table->string('jobs_description')->nullable();
            $table->string('skills_description')->nullable();

            $table->string('price_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learn_path_infos', function (Blueprint $table) {
            $table->dropColumn('avg_salary');
            $table->dropColumn('jobs_description');
            $table->dropColumn('skills_description');

            $table->dropColumn('price_description');
        });
    }
}
