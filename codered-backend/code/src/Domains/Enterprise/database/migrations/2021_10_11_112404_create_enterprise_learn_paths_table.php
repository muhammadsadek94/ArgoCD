<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterpriseLearnPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_learn_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('package_id')->nullable();
            $table->foreign('package_id')->references('id')
                ->on('package_subscriptions')->onDelete('set null');


            $table->uuid('enterprise_id')->nullable();
            $table->foreign('enterprise_id')->references('id')
                ->on('users')->onDelete('set null');

            $table->string('deadline_type')->nullable();
            $table->timestamp('expiration_date');
            $table->string('expiration_days');

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
        Schema::dropIfExists('enterprise_learn_paths');
    }
}
