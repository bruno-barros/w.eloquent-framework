<?php  namespace Weloquent\Core\Http;

use Illuminate\Http\Response;

/**
 * Router
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class Router extends \Illuminate\Routing\Router{

	/**
	 * Dispatch the request to the application.
	 *
	 * @param \Illuminate\Http\Request|Request $request
	 * @return Response
	 */
	public function dispatch(\Illuminate\Http\Request $request)
	{
		$this->currentRequest = $request;

		$response = $this->prepareResponse($request, $request);

		return $response;
	}

}