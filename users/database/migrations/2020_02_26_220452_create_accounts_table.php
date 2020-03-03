<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('user_id');
			$table->string('username')->unique();
			$table->enum('type', ['consumer', 'seller']);
			$table->string('cnpj')->nullable();
			$table->string('fantasy_name')->nullable();
			$table->string('social_name')->nullable();
            $table->timestamps();
			$table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('create_accounts');
    }
}
