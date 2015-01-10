<?php namespace Weloquent\Providers;

use Brain\Container;
use Brain\Cortex;
use Weloquent\Core\Http\Redirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Weloquent\Core\Http\Router;

/**
 * RouteServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class RouteServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Overcome router to my own implementation
		$this->app['router'] = $this->app->share(function($app)
		{
			$router = new Router($app['events'], $app);

			// If the current application environment is "testing", we will disable the
			// routing filters, since they can be tested independently of the routes
			// and just get in the way of our typical controller testing concerns.
			if ($app['env'] == 'testing')
			{
				$router->disableFilters();
			}

			return $router;
		});

		// w.eloquent router by Cortex
		$this->app->bindShared('weloquent.route', function ($app)
		{
			return Container::instance()->get('cortex.api');
		});

		$this->registerUrlGenerator();

		$this->registerRedirector();

	}

	/**
	 * Register the URL generator service.
	 *
	 * @return void
	 */
	protected function registerUrlGenerator()
	{
		$this->app['url'] = $this->app->share(function($app)
		{
			// The URL generator needs the route collection that exists on the router.
			// Keep in mind this is an object, so we're passing by references here
			// and all the registered routes will be available to the generator.
			$routes = $app['router']->getRoutes();

			return new UrlGenerator($routes, $app->rebinding('request', function($app, $request)
			{
				$app['url']->setRequest($request);
			}));
		});
	}


	/**
	 * Register the Redirector service.
	 *
	 * @return void
	 */
	protected function registerRedirector()
	{
		$this->app['redirect'] = $this->app->share(function ($app)
		{
			$redirector = new Redirector($app['url']);

			// If the session is set on the application instance, we'll inject it into
			// the redirector instance. This allows the redirect responses to allow
			// for the quite convenient "with" methods that flash to the session.
			if (isset($app['session.store']))
			{
				$redirector->setSession($app['session.store']);
			}

			return $redirector;
		});
	}
}