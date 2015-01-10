<?php namespace Weloquent\Providers;

use Illuminate\Support\ServiceProvider;
use Weloquent\Core\Globaljs\GlobalJs;

/**
 * GlobalJsServiceProvider
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class GlobalJsServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->bindShared('weloquent.globaljs', function ($app)
		{

			return new GlobalJs;

		});

		$this->registerFrontAction();

		$this->registerAdminAction();

	}

	private function registerFrontAction()
	{
		add_action('wp_head', function ()
		{

			$output = "<script id=\"weloquent_globaljs\" type=\"text/javascript\">\n\r";
			$output .= "//<![CDATA[\n\r";
			$output .= "var GJS = " . $this->app['weloquent.globaljs']->toJson();
			$output .= "\n\r//]]>\n\r";
			$output .= "</script>";

			// Output the datas.
			echo($output);

		});

	}
	private function registerAdminAction()
	{
		add_action('admin_head', function ()
		{

			$output = "<script id=\"weloquent_globaljs\" type=\"text/javascript\">\n\r";
			$output .= "//<![CDATA[\n\r";
			$output .= "var GJS = " . $this->app['weloquent.globaljs']->toJson(true);
			$output .= "\n\r//]]>\n\r";
			$output .= "</script>";

			// Output the datas.
			echo($output);

		});

	}
}