<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('payouts', function (Blueprint $table) {
            $table->string('outstanding_advances')->nullable();
            $table->string('royalties_carried_out')->nullable();
            $table->string('royalty')->nullable();
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('outstanding_advances');
            $table->dropColumn('royalties_carried_out');
            $table->dropColumn('royalty');
            $table->dropColumn('course_id');
        });
    }
}
