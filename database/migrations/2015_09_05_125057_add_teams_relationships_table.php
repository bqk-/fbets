<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamsRelationshipsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('teams_relations', function($table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('out_id')->unsigned();
            $table->string('championship_id');
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
        Schema::drop('teams_relations');
	}

}
