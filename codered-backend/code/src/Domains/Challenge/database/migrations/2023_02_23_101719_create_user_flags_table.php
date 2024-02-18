<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_flags', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('guest_id')->nullable();
            $table->foreign('guest_id')->references('id')->on('competition_guests')->onDelete('cascade');

            $table->string('competition_id')->nullable();

            $table->string('event_id')->nullable();
            $table->string('flag_id')->nullable();

            $table->string('total_time')->nullable();
            $table->string('time_taken')->nullable();

            $table->string('user_score')->nullable();
            
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
        Schema::dropIfExists('user_flags');
    }
}
