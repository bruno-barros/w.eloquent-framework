<?php namespace Weloquent\Providers;

use Brain\Container;
use Illuminate\Support\ServiceProvider;

/**
 * AssetsServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class AssetsServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('weloquent.assets', function ($app)
		{
			return Container::instance()->get('occipital.api');
		});
	}
}