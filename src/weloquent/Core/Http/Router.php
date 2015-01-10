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

		// If no response was returned from the before filter, we will call the proper
		// route instance to get the response. If no route is found a response will
		// still get returned based on why no routes were found for this request.
		$response = $this->callFilter('before', $request);

		/**
		 * Just ignoring the router dispatch lookinf for a router
		 * This is donne by WordPress
		 */
		//		if (is_null($response))
		//		{
		//			$response = $this->dispatchToRoute($request);
		//		}

		$response = $this->prepareResponse($request, $response);

		// Once this route has run and the response has been prepared, we will run the
		// after filter to do any last work on the response or for this application
		// before we will return the response back to the consuming code for use.
		$this->callFilter('after', $request, $response);

		return $response;
	}

}