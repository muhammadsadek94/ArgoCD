<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->uuid("country_id")->nullable();
            $table->foreign("country_id")->references("id")->on("countries")->onDelete('set null');

            $table->uuid("city_id")->nullable();
            $table->foreign("city_id")->references("id")->on("cities")->onDelete("set null");

            $table->integer('gender')->nullable();
            $table->date('birth_date')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn("country_id");
            $table->dropColumn("city_id");
            $table->dropColumn('gender');
            $table->dropColumn('birth_date');
        });
    }
}
