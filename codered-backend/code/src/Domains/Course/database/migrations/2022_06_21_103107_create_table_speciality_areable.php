<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSpecialityAreable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialty_areables', function (Blueprint $table) {
            $table->id('id');

            $table->uuid('specialty_area_id')->nullable();
            $table->foreign('specialty_area_id')->references('id')
                ->on('specialty_areas')->onUpdate('cascade')->onDelete('cascade');

            $table->uuid('specialty_areable_id');
            $table->string('specialty_areable_type');
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
        Schema::dropIfExists('specialty_areables');
    }
}
