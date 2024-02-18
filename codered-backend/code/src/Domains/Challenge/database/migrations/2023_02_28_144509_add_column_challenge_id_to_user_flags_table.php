<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChallengeIdToUserFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_flags', function (Blueprint $table) {
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
        Schema::table('user_flags', function (Blueprint $table) {
            $table->dropForeign('user_flags_challenge_id_foreign');
            $table->dropColumn('challenge_id');
        });
    }
}
