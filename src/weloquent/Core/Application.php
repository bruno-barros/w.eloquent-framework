<?php namespace Weloquent\Core;

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
	 * Run the application and send the response.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request $request
	 * @return void
	 */
	public function run(SymfonyRequest $request = null)
	{

		$self = $this;

		$request = $request ?: $self['request'];

		$response = with($stack = $self->getStackedClient())->handle($request);

		//		$response->send();
		$response->sendHeaders();



		if ('cli' !== PHP_SAPI)
		{
			$response::closeOutputBuffers(0, true);
		}
		//				dump($response->getContent());

		//		dd($response);

		$stack->terminate($request, $response);

		add_action('wp_footer', function () use ($self, $request)
		{
//			die('shutdown');
			Session::save();
			//			dd($this);

		});

	}

}