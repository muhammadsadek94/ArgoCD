<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChallengeIdToUserCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_competitions', function (Blueprint $table) {
            $table->uuid('challenge_id')->nullable()->after('id');
            $table->foreign('challenge_id')->references('id')->on('challenges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_competitions', function (Blueprint $table) {
            $table->dropForeign('user_competitions_challenge_id_foreign');
            $table->dropColumn('challenge_id');
        });
    }
}
