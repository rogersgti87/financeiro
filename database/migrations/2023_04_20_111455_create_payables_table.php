<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payables', function (Blueprint $table) {
            $table->id('id');
            $table->integer('category_id');
            $table->string('description')->nullable();
            $table->float('price', 8, 2)->default('0');
            $table->string('payment_method');
            $table->date('date_payable');
            $table->date('date_end');
            $table->string('period');
            $table->date('date_payment')->nullable();
            $table->enum('status', ['Cancelado','Pago','Nao pago']);
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
        Schema::dropIfExists('payables');
    }
}
