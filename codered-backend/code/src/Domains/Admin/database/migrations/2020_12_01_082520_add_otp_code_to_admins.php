<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpCodeToAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('phone')->unique()->nullable();
        });

        Schema::create('admin_2fa_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 4);
            $table->uuid("admin_id");
            $table->foreign("admin_id")->references("id")->on("admins")
                ->onUpdate("cascade")->onDelete("cascade");
            $table->boolean('used')->default(false);
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
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::dropIfExists('admin_2fa_tokens');
    }
}
