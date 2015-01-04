<?php namespace Weloquent\Core\Http;

use Illuminate\Http\RedirectResponse;

/**
 * Redirector
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class Redirector extends \Illuminate\Routing\Redirector
{


	/**
	 * Create a new redirect response.
	 *
	 * @param  string $path
	 * @param  int $status
	 * @param  array $headers
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function createRedirect($path, $status, $headers)
	{

		if (isset($this->session))
		{
			// save to keep data because it will be flushed by the redirect
			$this->session->save('redirection');
		}

		wp_redirect($path, $status);
		exit;
	}

}