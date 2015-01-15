<?php namespace Weloquent\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Ajax
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class Ajax extends Facade
{
	/**
	 * @throws \RuntimeException
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'weloquent.ajax';
	}

}