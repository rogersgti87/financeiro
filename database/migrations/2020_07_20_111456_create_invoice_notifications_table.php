<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_notifications', function (Blueprint $table) {
            $table->id('id');
            $table->integer('invoice_id');
            $table->string('type_send');
            $table->date('date');
            $table->string('senpulse_email_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('open')->nullable();
            $table->integer('click')->nullable();
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
        Schema::dropIfExists('invoice_notifications');
    }
}
