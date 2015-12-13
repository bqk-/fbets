<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuggestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suggestions', function($table)
        {
            $table->increments('id');
            $table->integer('id_user');
            $table->integer('id_sport');
            $table->string('championship');
            $table->string('team1');
            $table->string('team2');
            $table->timestamp('date');
            $table->tinyInteger('state');
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
		Schema::drop('suggestions');
	}

}
