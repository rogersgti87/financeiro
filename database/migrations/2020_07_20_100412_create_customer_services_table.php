<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id('id');
            $table->integer('customer_id');
            $table->integer('service_id');
            $table->string('dominio')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->float('price', 8, 2)->default('0');
            $table->string('period');
            $table->enum('status', ['cancelado','ativo','pendente','suspenso']);
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
        Schema::dropIfExists('customer_services');
    }
}
