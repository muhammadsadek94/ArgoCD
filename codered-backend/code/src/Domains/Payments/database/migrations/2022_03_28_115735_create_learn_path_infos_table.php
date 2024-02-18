<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnPathInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_path_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_url')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('price')->nullable();
            $table->string('payment_url')->nullable();
            $table->text('overview')->nullable();

            $table->string('for_who')->nullable();
            $table->json('learn')->nullable();
            $table->json('skills')->nullable();
            $table->json('jobs')->nullable();
            $table->json('prerequisite')->nullable();


            $table->json('faq')->nullable();

            $table->uuid('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('package_subscriptions')->cascadeOnDelete();


            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');

            $table->uuid('cover_id')->nullable();
            $table->foreign('cover_id')->references('id')->on('uploads')->onDelete('set null');

            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('course_categories')->onDelete('set null');


            $table->boolean('activation')->default(0);



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
        Schema::dropIfExists('learn_path_infos');
    }
}
