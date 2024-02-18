<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_weights', function (Blueprint $table) {
            $table->uuid('id');

            $table->uuid('package_subscription_id')->nullable();
            $table->foreign('package_subscription_id')->references('id')
                ->on('package_subscriptions')->onDelete('set null');

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('set null');

            $table->string('weight')->nullable();
            $table->integer('sort')->nullable();

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
        Schema::dropIfExists('course_weights');
    }
}
