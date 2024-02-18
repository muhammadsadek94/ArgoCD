<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSubCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learn_path_infos', function(Blueprint $table) {
            $table->uuid('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('course_categories')->onDelete('set null');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learn_path_infos', function(Blueprint $table) {
            $table->dropColumn('sub_category_id');
         });
    }
}
