<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTeamToIntInGames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('games', function($table)
        {
            $table->dropColumn(['team1', 'team2', 'logo1', 'logo2']);
        });

        Schema::table('games', function($table)
        {
            $table->integer('team1')->after('id_championship');
            $table->integer('team2')->after('team1');
            $table->dropColumn('week');
        });

        Schema::table('teams', function($table)
        {
            $table->integer('logo')->after('description');
        });

        Schema::table('championships', function($table)
        {
            $table->dropColumn('type');
            $table->dropColumn('url_first');
            $table->dropColumn('url_last');

        });

        Schema::table('championships', function($table)
        {
            $table->string('type')->after('id_sport');
            $table->text('params')->after('name');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('games', function($table)
        {
            $table->dropColumn(['team1', 'team2']);
        });

        Schema::table('games', function($table)
        {
            $table->string('team1')->after('id_championship');
            $table->integer('logo1')->after('team1');
            $table->string('team2')->after('logo1');
            $table->integer('logo1')->after('team2');
            $table->integer('week')->after('date');
        });

        Schema::table('championships', function($table)
        {
            $table->dropColumn('type');
            $table->dropColumn('params');
        });

        Schema::table('championships', function($table)
        {
            $table->integer('type')->after('id_sport');
            $table->string('url_first')->after('name');
            $table->string('url_last')->after('url_first');
        });
	}

}
