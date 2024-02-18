<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_competitions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('guest_id')->nullable();
            $table->foreign('guest_id')->references('id')->on('competition_guests')->onDelete('cascade');            

            $table->string('competition_id')->nullable();

            $table->string('event_id')->nullable();

            $table->boolean('is_lab_launched')->default(0);
            $table->boolean('is_lab_completed')->default(0);

            $table->string('started_at')->nullable();
            $table->string('completed_at')->nullable();

            $table->string('total_score')->nullable();
            $table->string('exam_score')->nullable();

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
        Schema::dropIfExists('user_competitions');
    }
}
