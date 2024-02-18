<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoursesSort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function(Blueprint $table) {
            $table->integer('sort')->default(0);
        });

        Schema::table('chapters', function(Blueprint $table) {
            $table->integer('sort')->default(0);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function(Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('chapters', function(Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
}
