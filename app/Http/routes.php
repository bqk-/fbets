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
    $serv2 = new \App\Services\ChampionshipService();
    switch ($type) {
        case 'championship':
            $champ = $serv2->GetWithGamesAndScores($id);
            $serv = new \App\Services\BetService();
            $bets = $serv->GetUserBetsForChampionship($id);

            return View::make('view/championship',array('champ' => $champ, 'bets' => $bets));
            break;
        
        default:

            $champs = $serv2->GetAllWithGames();

            return View::make('view/all')->with(array('champs' => $champs));
            break;
    }
});

Route::when('*', 'csrf', array('post'));

Route::controller('ajax', 'App\Http\Controllers\AjaxController');

Route::controller('group', 'App\Http\Controllers\GroupController');

Route::controller('javascript', 'App\Http\Controllers\JavascriptController');

//ADMIN ROUTES + FILTER
Route::group(['prefix' => 'admin'], function () {
    Route::get('users', function ()    {
        // Matches The "/admin/users" URL
    });
});
Route::when('admin*', 'admin');
Route::filter('admin', function()
{
    if(!(Auth::check() && in_array(Auth::user()->id, App\Http\Controllers\AdminController::$admins)))
        return Redirect::to('/');
});
Route::controller('admin', 'App\Http\Controllers\AdminController');

//What's left
Route::controller('/', 'App\Http\Controllers\HomeController');
