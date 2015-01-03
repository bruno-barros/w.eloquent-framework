<?php  namespace Weloquent\Providers;

use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Weloquent\Support\Session\RawSessionHandler;

/**
 * RawSessionServiceProvider
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class RawSessionServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
//		$this->package('stechstudio/laravel-raw-sessions');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		Session::extend('raw', function($app)
		{
			return new RawSessionHandler(Config::get("session.namespace"));
		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}