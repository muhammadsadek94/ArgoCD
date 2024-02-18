<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconClassNameToCourseCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_categories', function (Blueprint $table) {
            //
             $table->string('icon_class_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_categories', function (Blueprint $table) {
            //
             $table->dropColumn('icon_class_name');
        });
    }
}
