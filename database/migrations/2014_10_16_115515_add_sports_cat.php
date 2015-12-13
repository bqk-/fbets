<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSportsCat extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sports', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->integer('logo');
            $table->timestamps();
        });

        Schema::table('championships', function($table)
        {
            $table->integer('id_sport')->after('id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sports');

        Schema::table('championships', function($table)
        {
            $table->dropColumn('id_sport');
        });

	}

}
