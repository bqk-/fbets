<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreRatesWithBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function($table)
        {
            $table->dropColumn(['outcome', 'processed']);
        });
        
        Schema::table('games', function($table)
        {
            $table->float('rate_home')->after('date');
            $table->float('rate_draw')->after('rate_home');
            $table->float('rate_visit')->after('rate_draw');
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
            $table->tinyint('outcome')->after('status');
            $table->tinyint('processed')->after('outcome');
        });
        
        Schema::table('games', function($table)
        {
            $table->dropColumn(['rate_home', 'rate_draw', 'rate_visit']);
        });
    }
}
