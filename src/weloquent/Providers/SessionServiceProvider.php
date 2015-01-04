<?php namespace Weloquent\Providers;

use Illuminate\Support\Facades\Session;
use Weloquent\Core\Session\RawSessionHandler;
use Weloquent\Core\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

/**
 * SessionServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class SessionServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->setupDefaultDriver();

		$this->registerSessionManager();

		$this->registerSessionDriver();

		$this->registerRawSessionDriver();
	}

	/**
	 * Setup the default session driver for the application.
	 *
	 * @return void
	 */
	protected function setupDefaultDriver()
	{
		if ($this->app->runningInConsole())
		{
			$this->app['config']['session.driver'] = 'array';
		}
	}

	/**
	 * Register the session manager instance.
	 *
	 * @return void
	 */
	protected function registerSessionManager()
	{
		$this->app->bindShared('session', function($app)
		{
			return new SessionManager($app);
		});
	}

	/**
	 * Register the session driver instance.
	 *
	 * @return void
	 */
	protected function registerSessionDriver()
	{

		$this->app->bindShared('session.store', function($app)
		{
			// First, we will create the session manager which is responsible for the
			// creation of the various session drivers when they are needed by the
			// application instance, and will resolve them on a lazy load basis.
			$manager = $app['session'];

			return $manager->driver();
		});
	}

	protected function registerRawSessionDriver()
	{
		if($namespace = $this->app['config']['session.raw'])
		{
			Session::extend('raw', function($app) use ($namespace)
			{
				return new RawSessionHandler($namespace);
			});
		}

	}

}