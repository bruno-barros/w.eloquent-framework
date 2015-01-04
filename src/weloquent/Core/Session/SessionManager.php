<?php  namespace Weloquent\Core\Session;


/**
 * SessionManager
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class SessionManager extends \Illuminate\Session\SessionManager{

	/**
	 * Build the session instance.
	 *
	 * @param  \SessionHandlerInterface  $handler
	 * @return \Illuminate\Session\Store
	 */
	protected function buildSession($handler)
	{
		return new Store($this->app['config']['session.cookie'], $handler);
	}

}