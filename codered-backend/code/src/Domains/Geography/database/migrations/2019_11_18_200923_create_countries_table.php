<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("name_en")->nullable();
            $table->string("name_ar")->nullable();
            $table->string("iso")->nullable();
            $table->string("phone_code")->nullable();
            $table->integer("number_allow_digit")->nullable();
            $table->boolean("activation")->nullable();

            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');

            $table->string('currency_name_ar')->nullable();
            $table->string('currency_name_en')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string("nationality_en")->nullable();
            $table->string("nationality_ar")->nullable();
            $table->boolean("show_nationality")->default(0);
            $table->boolean("show_country")->default(0);
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
        Schema::dropIfExists('countries');
    }
}
