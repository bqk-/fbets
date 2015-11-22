<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPollStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('groups_polls', function(Blueprint $table)
		{
            $table->integer('status')->after('id_game');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('groups_polls', function(Blueprint $table)
		{
            $table->dropColumn('status');
		});
	}

}
