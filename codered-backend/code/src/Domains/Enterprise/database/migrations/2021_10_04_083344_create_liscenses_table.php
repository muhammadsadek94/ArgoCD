<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiscensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('license');
            $table->date('expired_at');
            $table->string('license_type');
            $table->string('duration');
            $table->string('status');

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->nullOnDelete();

            $table->uuid('enterprise_id')->nullable();
            $table->foreign('enterprise_id')->references('id')
                ->on('users')->nullOnDelete();

            $table->uuid('subaccount_id')->nullable();
            $table->foreign('subaccount_id')->references('id')
                ->on('users')->nullOnDelete();



            $table->boolean('activation')->default(0);


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}
