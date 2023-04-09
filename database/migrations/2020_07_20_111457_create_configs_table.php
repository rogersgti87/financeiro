<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id('id');
            $table->string('api_brasil_host')->nullable();
            $table->text('api_brasil_bearer_token')->nullable();
            $table->string('api_brasil_secret_key')->nullable();
            $table->string('api_brasil_device_token')->nullable();
            $table->string('api_brasil_public_token')->nullable();
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_user')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_security')->nullable();
            $table->string('sendpulse_token')->nullable();
            $table->string('sendpulse_secret')->nullable();
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
        Schema::dropIfExists('configs');
    }
}
