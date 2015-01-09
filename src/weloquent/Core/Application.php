<?php namespace Weloquent\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Application
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class Application extends \Illuminate\Foundation\Application
{

	/**
	 * The w.eloquent framework version.
	 *
	 * @var string
	 */
	const VERSION = '0.0.1';


	/**
	 * Alias to get App instance
	 *
	 * @return mixed
	 */
	public function getInstance()
	{
		return $this;
	}

	/**
	 * Run the application and save headers.
	 * The response is up to WordPress
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request $request
	 * @return void
	 */
	public function run(SymfonyRequest $request = null)
	{

		$request = $request ?: $this['request'];

		$response = with($stack = $this->getStackedClient())->handle($request);

		// just set headers, but the content
		if(! is_admin())
		{
			$response->sendHeaders();
		}

		if ('cli' !== PHP_SAPI)
		{
			$response::closeOutputBuffers(0, true);
		}

		$stack->terminate($request, $response);

		/**
		 * Save the session data until the application dies
		 */
		add_action('wp_footer', function ()
		{
			Session::save('wp_footer');

		});

	}

}