<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->string('current_employer')->nullable();
            $table->string('designation')->nullable();

            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('blog_url')->nullable();
            $table->string('article_url')->nullable();

            $table->string('years_experience')->nullable();
            $table->text('profile_summary')->nullable();

            $table->uuid('cv_id')->nullable();
            $table->foreign('cv_id')
                ->references('id')
                ->on('uploads')
                ->onDelete('set null');

            $table->boolean('have_courses')->default(0);
            $table->string('course_information')->nullable();

            $table->boolean('interested_video')->default(0);
            $table->boolean('interested_assessments')->default(0);
            $table->boolean('interested_written_materials')->default(0);

            $table->boolean('have_trending_course')->default(0);
            $table->text('trending_course_description')->nullable();
            $table->text('trending_course_topic')->nullable();
            $table->text('trending_course_target_audience')->nullable();

            $table->uuid('video_sample_id')->nullable();
            $table->foreign('video_sample_id')
                ->references('id')
                ->on('uploads')
                ->onDelete('set null');


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
        Schema::dropIfExists('instructor_profiles');
    }
}
