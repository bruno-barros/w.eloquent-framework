<?php namespace Weloquent\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * GlobalJs
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class GlobalJs extends Facade
{

	protected static function getFacadeAccessor()
	{
		return 'weloquent.globaljs';
	}

}