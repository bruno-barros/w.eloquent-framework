<?php  namespace Weloquent\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Hooks
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class Hooks extends Facade{

	/**
	 * @throws \RuntimeException
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'weloquent.hooks';
	}

}