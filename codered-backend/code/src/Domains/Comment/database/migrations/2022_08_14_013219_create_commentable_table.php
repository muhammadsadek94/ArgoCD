<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentables', function (Blueprint $table) {
            $table->id('id');


            $table->uuid('comment_id')->nullable();
            $table->foreign('comment_id')->references('id')
                ->on('comments')->onUpdate('cascade')->onDelete('cascade');


            $table->uuid('commentable_id');
            $table->string('commentable_type');

            $table->uuid('model_id');
            $table->string('model_type');

            $table->uuid('show_to_id');
            $table->string('show_to_type');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commentables');
    }
}
