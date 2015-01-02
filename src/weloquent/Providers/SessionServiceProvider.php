<?php namespace Weloquent\Providers;

use Illuminate\Session\SessionManager;
use Illuminate\Session\SessionServiceProvider as LaravelServiceProvider;
use Illuminate\Support\ServiceProvider;
use Weloquent\Support\Session\CustomSessionManager;
use Weloquent\Support\Session\LaravelSessionManager;

/**
 * SessionServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class SessionServiceProvider extends ServiceProvider
{

	/**
	 * Register the manager laravel should use to manage sessions
	 *
	 * @return SessionManager
	 */
//	protected function registerSessionManager()
//	{
//		$this->app->bindShared('session', function ($app)
//		{
//			// Tell Laravel to use our session manager
//			return new CustomSessionManager($app);
//		});
//	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$manager = new LaravelSessionManager($this->app);
		$manager->startSession();
	}
}