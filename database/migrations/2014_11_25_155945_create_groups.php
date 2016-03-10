<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('money');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('groups_users', function($table)
        {
            $table->integer('id_group');
            $table->integer('id_user');
        });

        Schema::create('groups_games', function($table)
        {
            $table->integer('id_group');
            $table->integer('id_game');
        });

        Schema::create('groups_notifications', function($table)
        {
            $table->increments('id');
            $table->integer('id_group');
            $table->integer('id_user');
            $table->integer('type');
            $table->integer('id_poll');
            $table->timestamp('date');
        });

        Schema::create('groups_requests', function($table)
        {
            $table->increments('id');
            $table->integer('id_group');
            $table->integer('id_user');
            $table->integer('from');
            $table->string('message');
            $table->integer('id_poll');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('groups_chats', function($table)
        {
            $table->increments('id');
            $table->integer('id_group');
            $table->integer('id_user');
            $table->string('message');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('groups_polls', function($table)
        {
            $table->increments('id');
            $table->integer('id_group');
            $table->integer('id_user');
            $table->integer('id_game');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('groups_polls_users', function($table)
        {
            $table->integer('id_poll');
            $table->integer('id_user');
            $table->integer('opinion');
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
		Schema::drop('groups');
        Schema::drop('groups_users');
        Schema::drop('groups_games');
        Schema::drop('groups_notifications');
        Schema::drop('groups_requests');
        Schema::drop('groups_chats');
        Schema::drop('groups_polls');
        Schema::drop('groups_polls_users');
	}

}
