<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('quote')->nullable();
            $table->text('author_name')->nullable();
            $table->text('author_position')->nullable();
            $table->uuid('author_image_id')->nullable();
            $table->foreign('author_image_id')->references('id')->on('uploads')->onDelete('set null');
            $table->boolean('activation')->default(0)->nullable();


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
        Schema::dropIfExists('quotes');
    }
}
