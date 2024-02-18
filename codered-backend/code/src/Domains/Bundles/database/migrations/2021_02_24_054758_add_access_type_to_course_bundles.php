<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessTypeToCourseBundles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('course_bundles', function (Blueprint $table) {
           $table->dropColumn('course_id');
           $table->dropColumn('deal_end_date');
        });


        Schema::table('course_bundles', function (Blueprint $table) {
            
            $table->boolean('access_type')->nullable();
            $table->json('access_id')->nullable();
            $table->date('deal_end_date')->nullable();
            
            
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
           $table->json('course_id')->nullable();
           $table->date('deal_end_date')->nullable();
        });


        Schema::table('course_bundles', function (Blueprint $table) {
              $table->dropColumn('access_type');
              $table->dropColumn('access_id');
              $table->dropColumn('deal_end_date');
        });
    }
}
