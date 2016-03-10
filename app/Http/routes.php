<?php

use App\Models\Data\Championship;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/404', function() {
    return View::make('home/404');
});

//TODO: Move to a viewController handling view event, championship, team, whatever
Route::get('/view/{type?}/{id?}', function($type = null, $id = null)
{
    $serv2 = \App::make('\App\Services\ChampionshipService');
    switch ($type) {
        case 'championship':
            $champ = $serv2->GetWithGamesAndScores($id);
            $serv = \App::make('\App\Services\BetService');
            $bets = $serv->GetUserBetsForChampionship($id);

            return View::make('view/championship',array('champ' => $champ, 'bets' => $bets));
            break;
        
        default:

            $champs = $serv2->GetAllWithGames();

            return View::make('view/all')->with(array('champs' => $champs));
            break;
    }
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::controller('ajax', 'App\Http\Controllers\AjaxController');

    Route::controller('javascript', 'App\Http\Controllers\JavascriptController');

    Route::group(['middleware' => ['admin']], function () {
        Route::controller('admin', 'App\Http\Controllers\AdminController');
    });
    
    Route::group(['middleware' => ['auth']], function () {
        Route::controller('group', 'App\Http\Controllers\GroupController');
    });

    //What's left
    Route::controller('/', 'App\Http\Controllers\HomeController');
});