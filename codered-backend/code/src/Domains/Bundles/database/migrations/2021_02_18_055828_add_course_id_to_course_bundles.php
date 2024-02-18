<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseIdToCourseBundles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_bundles', function (Blueprint $table) {
            //This column is used to save all the course_ids in json which was added for each bundle during creation of a bundle by Admin. 
              $table->json('course_id')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_bundles', function (Blueprint $table) {
              $table->dropColumn('course_id');
        });
    }
}
