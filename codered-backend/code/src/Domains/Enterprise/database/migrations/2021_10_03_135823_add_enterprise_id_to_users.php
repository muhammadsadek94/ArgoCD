<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnterpriseIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('enterprise_id')->nullable();
            $table->foreign('enterprise_id')->references('id')
                ->on('users')->nullOnDelete();

            $table->uuid('subaccount_id')->nullable();
            $table->foreign('subaccount_id')->references('id')
                ->on('users')->nullOnDelete();
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
            $table->dropColumn('enterprise_id');
            $table->dropColumn('subaccount_id');
        });
    }
}
