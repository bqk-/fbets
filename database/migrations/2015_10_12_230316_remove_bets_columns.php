<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBetsColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('bets', function($table)
        {
            $table->dropColumn(['right1', 'right2', 'score1', 'score2']);
            $table->integer('bet')->after('id_game');
        });

        Schema::table('results', function($table)
        {
            $table->integer('state')->after('team2');
            $table->integer('prediction')->after('team2');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('bets', function($table)
        {
            $table->integer('right2')->after('id_game');
            $table->integer('right1')->after('id_game');
            $table->integer('score2')->after('id_game');
            $table->integer('score1')->after('id_game');
            $table->dropColumn('bet');
        });

        Schema::table('results', function($table)
        {
            $table->dropColumn(['state', 'prediction']);
        });
	}

}
