<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->boolean('activation')->default(0);

            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');

            $table->uuid('course_category_id')->nullable();
            $table->foreign('course_category_id')->references('id')
                ->on('course_categories')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('course_tag_user', function(Blueprint $table) {
            $table->id();
            $table->uuid('course_tag_id')->nullable();
            $table->foreign('course_tag_id')->references('id')
                ->on('course_tags')->onDelete('cascade');

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_tag_user');
        Schema::dropIfExists('course_tags');
    }
}
