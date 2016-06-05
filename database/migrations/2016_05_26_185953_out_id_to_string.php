<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OutIdToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams_relations', function ($table) {
            $table->string('out_id', 255)->change();
            $table->integer('championship_id')->unsigned()->change();
        });
        
        Schema::table('games_relations', function ($table) {
            $table->string('out_id', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams_relations', function ($table) {
            $table->integer('out_id')->unsigned()->change();
            $table->string('championship_id', 255)->change();
        });
        
        Schema::table('games_relations', function ($table) {
            $table->integer('out_id')->unsigned()->change();
        });
    }
}
