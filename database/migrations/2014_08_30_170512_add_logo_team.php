<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogoTeam extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('games', function(Blueprint $table)
		{
			$table->integer('logo1')->after('team1');
			$table->integer('logo2')->after('team2');
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
			$table->dropColumn('logo1');
			$table->dropColumn('logo2');
		});
	}

}
