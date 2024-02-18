<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_enrollment', function (Blueprint $table) {
            $table->uuid('user_subscription_id')->nullable();
            $table->foreign('user_subscription_id')->references('id')
                ->on('user_subscriptions')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_enrollment', function (Blueprint $table) {
            $table->dropForeign(['user_subscription_id']);
            $table->dropColumn('user_subscription_id');
        });
    }
};
