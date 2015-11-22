<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bets', function($table)
		{
		    $table->increments('id');
		    $table->integer('id_user');
		    $table->integer('id_game');
		    $table->integer('score1');
		    $table->integer('score2');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bets');
	}

}
