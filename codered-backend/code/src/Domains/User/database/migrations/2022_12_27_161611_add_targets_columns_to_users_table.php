<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetsColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('weekly_target')->default(0);
            $table->json('selected_days')->nullable();
            $table->date('week_start_date')->nullable();
            $table->date('week_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('weekly_target');
            $table->dropColumn('selected_days');
            $table->dropColumn('week_start_date');
            $table->dropColumn('week_end_date');
        });
    }
}
