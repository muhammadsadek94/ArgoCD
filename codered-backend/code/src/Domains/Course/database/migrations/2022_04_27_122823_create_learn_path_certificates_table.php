<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnPathCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_path_certificates', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->uuid('learnpath_id')->nullable();
            $table->foreign('learnpath_id')->references('id')
                ->on('learn_path_infos')->onDelete('set null');

            $table->uuid('certificate_id')->nullable();
            $table->foreign('certificate_id')->references('id')
                ->on('uploads')->onDelete('set null');
            $table->string('certificate_number')->nullable();
            $table->string('degree')->nullable();
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
        Schema::dropIfExists('learn_path_certificates');
    }
}
