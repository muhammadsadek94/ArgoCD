<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoverImageToCourseBundles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_bundles', function (Blueprint $table) {
             $table->uuid('cover_image_id')->nullable();
             $table->foreign('cover_image_id')->references('id')->on('uploads')->onDelete('set null');
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
            $table->dropColumn('cover_image_id');
        });
    }
}
