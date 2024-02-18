<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobRollable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('job_rollables', function (Blueprint $table) {
            $table->id('id');

            $table->uuid('job_role_id')->nullable();
            $table->foreign('job_role_id')->references('id')
                ->on('job_roles')->onUpdate('cascade')->onDelete('cascade');

            $table->uuid('job_rollable_id');
            $table->string('job_rollable_type');
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
        Schema::dropIfExists('job_rollables');

    }
}
