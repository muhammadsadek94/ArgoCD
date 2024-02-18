<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('payment_transactions_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('set null');

            $table->uuid('payment_integration_id')->nullable();
            $table->foreign('payment_integration_id')->references('id')
                ->on('payment_integrations')->onDelete('set null');

            $table->nullableUuidMorphs('payable');
            $table->text('more_info')->nullable();
            $table->string('amount')->nullable();
            $table->string('order_id')->nullable();
            $table->string('status')->nullable();

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
        Schema::dropIfExists('payment_transactions_histories');
    }
}