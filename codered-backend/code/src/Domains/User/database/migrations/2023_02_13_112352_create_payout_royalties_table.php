<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutRoyaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_royalties', function (Blueprint $table) {
            $table->id();
            $table->string('outstanding_advances')->nullable();
            $table->string('royalties_carried_out')->nullable();
            $table->string('royalty')->nullable();
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            $table->uuid('payout_id')->nullable();
            $table->foreign('payout_id')->references('id')->on('payouts')->onDelete('set null');
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('payout_royalties');
    }
}
