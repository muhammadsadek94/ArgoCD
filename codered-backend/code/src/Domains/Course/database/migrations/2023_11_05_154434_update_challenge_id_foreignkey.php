<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            // The constraint name is usually formatted like table_column_foreign, but you should confirm this in your database.
            $table->dropForeign(['challenge_id']);

            // You don't need to change the challenge_id to nullable since it's already nullable based on your current schema.

            // Add the new foreign key constraint with onDelete('set null')
            $table->foreign('challenge_id')
                ->references('id')->on('challenges')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the updated foreign key constraint
            $table->dropForeign(['challenge_id']);

            // Re-add the original foreign key constraint with onDelete('cascade')
            $table->foreign('challenge_id')
                ->references('id')->on('challenges')
                ->onDelete('set null');
        });

    }
};
