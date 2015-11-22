<?php namespace App\Http\Controllers;

use \View;
use \Response;

class JavascriptController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Ajax Controller
    |--------------------------------------------------------------------------
    |
    | Manage everything ajax related
    |
    |    Route::controller('ajax', 'AjaxController');
    |
    */

    public function getMain()
    {
            $view = View::make('javascript/main')
                     ->render();

            return Response::make($view, 200, array('content-type' => 'application/javascript'));
    }

    public function getAdmin()
    {
        $view = View::make('javascript/admin')
                     ->render();

            return Response::make($view, 200, array('content-type' => 'application/javascript'));
    }

}
