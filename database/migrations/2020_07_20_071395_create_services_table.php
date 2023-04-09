<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->float('price', 8, 2)->default('0');
            $table->float('price_trimestral', 8, 2)->nullable();
            $table->float('price_anual', 8, 2)->nullable();
            $table->string('period');
            $table->enum('status', ['cancelado','ativo','pendente',]);
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
        Schema::dropIfExists('services');
    }
}
