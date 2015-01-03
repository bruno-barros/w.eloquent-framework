<?php namespace Weloquent\Core;

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
		//		$this->sendContent();
		//		dd($response);

		$stack->terminate($request, $response);

		add_action('shutdown', function () use ($self, $request)
		{
			dd($this);

		});

	}

}