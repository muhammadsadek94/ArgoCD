<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('comment');

                $table->uuid('owner_id');
                $table->string('owner_type');

                $table->uuid('entity_id');
                $table->string('entity_type');

                $table->boolean("activation")->default(true);

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
        Schema::dropIfExists('comments');
    }
}
