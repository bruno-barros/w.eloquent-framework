<?php  namespace Weloquent\Support\Session;


/**
 * CustomSessionManager
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class CustomSessionManager {
	/**
	 * All session drivers will now use our session storage class
	 *
	 * @param type $handler
	 * @return CustomSessionStore
	 */
	protected function buildSession($handler)
	{
		return new CustomSessionStore($this->app['config']['session.cookie'], $handler);
	}
}