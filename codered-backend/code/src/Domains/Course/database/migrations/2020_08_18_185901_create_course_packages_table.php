<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('amount')->nullable();
            $table->integer('type');
            $table->text('features')->nullable();
            $table->string('url')->nullable();
            
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('course_microdegrees', function(Blueprint $table) {
            $table->dropColumn('one_time_fees');
            $table->dropColumn('installments_fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_packages');
        Schema::table('course_microdegrees', function(Blueprint $table) {
            $table->decimal('one_time_fees', 9, 3)->nullable();
            $table->decimal('installments_fees', 9, 3)->nullable();
        });
    }
}
