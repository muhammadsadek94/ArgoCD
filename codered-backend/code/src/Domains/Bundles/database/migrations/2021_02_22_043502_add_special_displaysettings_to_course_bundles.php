<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialDisplaysettingsToCourseBundles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_bundles', function (Blueprint $table) {
            
             $table->text('bestseller_brief')->nullable();
             $table->text('newarrival_brief')->nullable();
             $table->boolean('is_bundle_spotlight')->default(0)->nullable();
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
              $table->dropColumn('bestseller_brief');
              $table->dropColumn('newarrival_brief');
              $table->dropColumn('is_bundle_spotlight');
        });
    }
}
