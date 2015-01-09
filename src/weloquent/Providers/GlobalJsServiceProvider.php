<?php  namespace Weloquent\Providers;

use Illuminate\Support\ServiceProvider;
use Weloquent\Core\Globaljs\GlobalJs;

/**
 * GlobalJsServiceProvider
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class GlobalJsServiceProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->bindShared('weloquent.globaljs', function($app){

			return new GlobalJs;

		});

		$this->registerAdminAction();


	}

	private function registerAdminAction()
	{
		/*----------------------------------------------------*/
		// Allow developers to add parameters to
		// the admin global JS object.
		/*----------------------------------------------------*/
		add_action('admin_head', function(){

			dd($this->app['weloquent.globaljs']->toJson(true));

			$output = "<script id=\"weloquent_globaljs\" type=\"text/javascript\">\n\r";
			$output.= "//<![CDATA[\n\r";
			$output.= "var weloquent = {\n\r";

			//			if (!empty($datas))
			//			{
			//				foreach ($datas as $key => $value)
			//				{
			//					$output.= $key.": ".json_encode($value).",\n\r";
			//				}
			//			}

			$output.= "};\n\r";
			$output.= "//]]>\n\r";
			$output.= "</script>";

			// Output the datas.
			echo($output);

		});

	}
}