<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsSlider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
          $table->string('title_color', 199)->nullable();
          $table->string('sub_title_color', 199)->nullable();

          $table->string('description_color', 199)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('title_color');
            $table->dropColumn('sub_title_color');

            $table->dropColumn('description_color');

          });
    }
}
