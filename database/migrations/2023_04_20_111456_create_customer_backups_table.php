<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_backups', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('google_drive_folder_sql')->nullable();
            $table->string('google_drive_folder_file')->nullable();
            $table->string('folder_path')->nullable();
            $table->string('database')->nullable();
            $table->string('host')->nullable();
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->string('port')->nullable();
            $table->enum('status', ['Ativo','Inativo']);
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
        Schema::dropIfExists('customer_backups');
    }
}
