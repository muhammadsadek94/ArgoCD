<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLearnPathIdColumnToPackageSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_subscriptions', function (Blueprint $table) {
            $table->uuid('learn_path_id')->nullable();
            $table->foreign('learn_path_id')->references('id')->on('learn_path_infos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['learn_path_id']);
            $table->dropColumn('learn_path_id');
        });
    }
}
