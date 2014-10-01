<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pal_users', function(Blueprint $table)
		{
            $table->string('id', 100);
            $table->integer('host_id');
            $table->string('realname', 50);
            $table->string('username', 50);
            $table->string('url', 200);
            $table->string('credentials');
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
		Schema::drop('pal_users');
	}

}
