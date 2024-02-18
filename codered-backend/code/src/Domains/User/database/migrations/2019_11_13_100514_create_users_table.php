<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("password")->nullable();
            $table->string("email")->unique()->nullable();
            $table->string("phone")->unique()->nullable();
            $table->string('activation')->nullable();
            $table->integer("type")->default(0);

            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');

            $table->string("password_reset_code")->nullable();
            $table->string("temp_email_code")->nullable();
            $table->string("temp_phone_code")->nullable();
            $table->string("social_type")->nullable();
            $table->string("social_id")->nullable();
            $table->string("language")->nullable();

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
        Schema::dropIfExists('users');
    }
}
