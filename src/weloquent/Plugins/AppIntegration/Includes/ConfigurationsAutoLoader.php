<?php namespace Weloquent\Plugins\AppIntegration\Includes;

use Weloquent\Core\Contracts\LoaderInterface;
use Weloquent\Core\Loader;

class ConfigurationsAutoLoader extends Loader implements LoaderInterface
{
	/**
	 * Load the files on app/autoload
	 * 
	 * @return bool True. False if not appended.
	 */
	public static function add()
	{
		return static::append(self::$app['path'].'/autoload');
	}

}