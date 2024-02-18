<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->text('brief')->nullable();
            $table->text('intro_video')->nullable();
            $table->text('description')->nullable();

            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->json('learn')->nullable();

            $table->uuid('course_category_id')->nullable();
            $table->foreign('course_category_id')->references('id')
                ->on('course_categories')->onDelete('set null');

            $table->integer('level')->nullable();
            $table->integer('timing')->nullable();

            $table->uuid('syllabus_id')->nullable();
            $table->foreign('syllabus_id')->references('id')->on('uploads')->onDelete('set null');

            $table->boolean('is_featured')->default(0);
            $table->integer('course_type')->default(0);
            $table->boolean('activation')->default(0);

            $table->decimal('fees', 9, 3)->nullable();



            $table->timestamps();
        });

        Schema::create('course_course_tag', function(Blueprint $table) {
            $table->id();
            $table->uuid('course_tag_id')->nullable();
            $table->foreign('course_tag_id')->references('id')
                ->on('course_tags')->onDelete('cascade');

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');


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
        Schema::dropIfExists('course_course_tag');
        Schema::dropIfExists('courses');
    }
}
