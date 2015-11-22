<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeResultsSeparateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('games', function(Blueprint $table)
		{
			$table->dropColumn('result');
		});

		Schema::create('results', function($table)
		{
		    $table->increments('id');
		    $table->integer('id_game');
		    $table->integer('team1');
		    $table->integer('team2');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('games', function(Blueprint $table)
		{
			$table->integer('result')->after('team2');
		});

		Schema::drop('results');
	}

}
