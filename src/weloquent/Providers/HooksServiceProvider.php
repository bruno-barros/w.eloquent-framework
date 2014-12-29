<?php namespace Weloquent\Providers;

use Brain\Container;
use Illuminate\Support\ServiceProvider;

/**
 * HooksServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class HooksServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

			$this->app->bindShared('weloquent.hooks', function ($app)
			{
				return Container::instance()->get('hooks.api');
			});

	}
}