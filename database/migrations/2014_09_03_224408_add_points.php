<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoints extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bets', function(Blueprint $table)
		{
			$table->tinyInteger('outcome')->after('score2')->default(0);
			$table->tinyInteger('right1')->after('outcome')->default(0);
			$table->tinyInteger('right2')->after('right1')->default(0);
			$table->tinyInteger('processed')->after('right2')->default(0);
		});

		Schema::table('users', function(Blueprint $table)
		{
			$table->integer('points')->after('remember_token')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bets', function(Blueprint $table)
		{
			$table->dropColumn('outcome');
			$table->dropColumn('right1');
			$table->dropColumn('right2');
			$table->dropColumn('processed');
		});

		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('points');
		});
	}

}
