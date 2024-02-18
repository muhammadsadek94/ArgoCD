<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_partner', function (Blueprint $table) {
            $table->id();
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')
                ->onDelete('cascade');

            $table->uuid('partner_id')->nullable();
            $table->foreign('partner_id')->references('id')->on('partners')
                ->onDelete('cascade');

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
        Schema::dropIfExists('course_partner');
    }
}
