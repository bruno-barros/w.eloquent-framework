<?php namespace Weloquent\Providers;

use Illuminate\Support\ServiceProvider;
use Weloquent\Facades\Ajax;

/**
 * AjaxServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class AjaxServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('weloquent.ajax', function ($app)
		{
			return new Ajax($app);
		});
	}
}