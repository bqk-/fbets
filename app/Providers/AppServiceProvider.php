<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
            \Validator::extend('alpha_spaces', function($attribute, $value)
            {
                return preg_match('/^([\p{L}0-9_\-\s])+$/i', $value);
            });
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
            $this->app->bind(
                    'Illuminate\Contracts\Auth\Registrar',
                    'App\Services\Registrar'
            );
            
            $this->app->bind(
                'App\Services\Contracts\ICurrentUser',
                'App\Models\Services\CurrentUser'
            );
	}

}
