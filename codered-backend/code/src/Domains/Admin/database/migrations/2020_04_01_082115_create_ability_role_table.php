<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilityRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ability_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid("ability_id");
            $table->foreign("ability_id")->references("id")->on("abilities")
                ->onUpdate("cascade")->onDelete("cascade");

            $table->uuid("role_id");
            $table->foreign("role_id")->references("id")->on("roles")
                ->onUpdate("cascade")->onDelete("cascade");
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
        Schema::dropIfExists('ability_role');
    }
}
