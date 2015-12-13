<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function($table) {
        $table->increments('id');
        $table->integer('id_championship')->unsigned();
        $table->string('team1');
        $table->string('team2');
        $table->integer('result')->unsigned()->default(0);
        $table->timestamp('date');
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
		Schema::drop('games');
	}

}
