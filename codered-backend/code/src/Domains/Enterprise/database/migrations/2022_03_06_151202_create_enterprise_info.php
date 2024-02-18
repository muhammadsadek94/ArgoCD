<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterpriseInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('licenses_reuse_number');

            $table->uuid('enterprise_id')->nullable();
            $table->foreign('enterprise_id')->references('id')
                ->on('users')->cascadeOnDelete();

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
        Schema::dropIfExists('enterprise_infos');
    }
}
