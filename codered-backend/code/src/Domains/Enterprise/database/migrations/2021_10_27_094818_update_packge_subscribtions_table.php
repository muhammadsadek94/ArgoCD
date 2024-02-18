<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePackgeSubscribtionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_subscriptions', function (Blueprint $table) {

            $table->string('deadline_type')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->string('expiration_days')->nullable();

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

            $table->dropColumn('deadline_type');
            $table->dropColumn('expiration_date');
            $table->dropColumn('expiration_days');

        });
    }
}
