<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_bundles', function (Blueprint $table) {
             $table->uuid('id')->primary();
             $table->string('name');
             $table->text('description')->nullable();
             $table->uuid('image_id')->nullable();
             $table->foreign('image_id')->references('id')->on('uploads')->onDelete('set null');
             $table->text('features')->nullable();
             $table->integer('display_status')->default(0);
             $table->boolean('is_bestseller')->default(0)->nullable();
             $table->boolean('is_new_arrival')->default(0)->nullable();
             $table->json('jobs')->nullable();
             $table->json('topics')->nullable();
             $table->json('certifications')->nullable();
             $table->integer('bundle_type')->default(0);

             $table->string('payment_title');
             $table->string('price');
             $table->string('sale_price')->nullable();
             $table->integer('price_period')->nullable();
             $table->string('bundle_url')->nullable();
             $table->string('access_pass_url')->nullable();
             $table->text('price_features')->nullable();

             $table->json('learn_features')->nullable();
             $table->json('bundle_spotlight')->nullable();

             $table->date('deal_end_date');

             $table->boolean('activation')->default(0);
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
        Schema::dropIfExists('course_bundles');
    }
}
