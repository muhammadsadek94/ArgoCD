<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->string('payee_name')->nullable();
            $table->string('payee_bank_country')->nullable();
            $table->string('payee_branch_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('intermediary_bank')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('payee_bank_for_tt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->dropColumn('payee_name');
            $table->dropColumn('payee_bank_country');
            $table->dropColumn('payee_branch_name');
            $table->dropColumn('branch_code');
            $table->dropColumn('intermediary_bank');
            $table->dropColumn('routing_number');
            $table->dropColumn('payee_bank_for_tt');
        });
    }
};
