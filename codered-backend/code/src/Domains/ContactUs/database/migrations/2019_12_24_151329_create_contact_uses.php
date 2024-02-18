<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_uses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->text("body")->nullable();
            $table->boolean("activation")->default(0);
            $table->smallInteger("app_type")->nullable();

            $table->string("subject_id")->nullable();
            $table->foreign("subject_id")->references("id")->on("contact_us_subjects")->onDelete("set null");
            $table->boolean("status")->default(0);

            $table->uuid("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("set null");
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
        Schema::dropIfExists('contact_uses');
    }
}
