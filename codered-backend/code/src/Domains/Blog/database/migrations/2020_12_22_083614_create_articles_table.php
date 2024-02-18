<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('content');
            $table->json('tags')->nullable();
            $table->uuid("article_category_id")->nullable();
            $table->foreign("article_category_id")
                ->references("id")
                ->onDelete('set null')
                ->on("article_categories");

            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');

            $table->uuid('internal_image_id')->nullable();
            $table->foreign('internal_image_id')->references('id')->on('uploads')->onDelete('set null');


            $table->boolean('is_featured')->default(0)->nullable();
            $table->boolean('activation')->default(0)->nullable();
            $table->integer('views')->default(0)->nullable();

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
        Schema::dropIfExists('articles');
    }
}
