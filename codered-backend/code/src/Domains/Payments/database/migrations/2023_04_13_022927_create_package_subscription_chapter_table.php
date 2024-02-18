<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_subscription_chapter', function (Blueprint $table) {
            $table->integer('id', true);

            $table->uuid('package_subscription_id')->nullable();
            $table->foreign('package_subscription_id')->references('id')
                ->on('package_subscriptions')->onDelete('cascade');

            $table->uuid('chapter_id')->nullable();
            $table->foreign('chapter_id')->references('id')
                ->on('chapters')->onDelete('cascade');

            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');

            $table->integer('after_installment_number')->default(0);
            $table->boolean('is_free_trial')->default(false);

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
        Schema::dropIfExists('package_subscription_chapter');
    }
};
