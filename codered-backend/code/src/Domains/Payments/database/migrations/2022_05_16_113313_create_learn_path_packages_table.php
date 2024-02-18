<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnPathPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_path_packages', function (Blueprint $table) {

                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('amount')->nullable();
                $table->integer('type')->nullable();
                $table->text('features')->nullable();
                $table->string('url')->nullable();

                $table->uuid('path_id')->nullable();
                $table->foreign('path_id')->references('id')
                    ->on('learn_path_infos')->onDelete('cascade');
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
        Schema::dropIfExists('learn_path_packages');
    }
}
