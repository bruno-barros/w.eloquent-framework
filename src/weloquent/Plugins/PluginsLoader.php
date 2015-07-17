<?php namespace Weloquent\Plugins;

use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

/**
 * PluginsLoader
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class PluginsLoader
{

	/**
	 * Path to required plugins
	 *
	 * @var array
	 */
	private static $required = [

		// Laravel application inside WP
		'../Core/LaravelApplication.php',

		// w.eloquent modifications
		'AppIntegration/app-integration.php',

	];

	/**
	 * Short names for incorporated plugins
	 *
	 * @var array
	 */
	private static $lookup = [

		'blade' => 'Blade/blade.php',

		'brain' => 'BrainPlugins/brain-plugins.php',

	];

	/**
	 * Load required plugins
	 */
	public static function bootRequired()
	{
		// on test do not load plugins
		if(self::isUnableToRunPlugins())
		{
			return;
		}

		foreach (self::$required as $plugin)
		{
			require_once __DIR__ . DS . $plugin;
		}

	}

	/**
	 * Require plugins based on an array of paths
	 *
	 * @param $path
	 */
	public static function loadFromPath($path)
	{
		// on test do not load plugins
		if(self::isUnableToRunPlugins())
		{
			return;
		}

		if (!file_exists($path))
		{
			throw new InvalidArgumentException("The path [{$path}] doesn't exist to load plugins.");
		}

		$plugins = require $path;

		foreach ($plugins as $plugin)
		{
			if (array_key_exists($plugin, self::$lookup))
			{
				$plugin = __DIR__ . DS . self::$lookup[$plugin];
			}

			if (file_exists($plugin))
			{
				require_once $plugin;
			}
		}

	}

	/**
	 * Check if is on test or installing WordPress
	 * or not using themes (typically running inside Laravel)
	 *
	 * @return bool
	 */
	private static function isUnableToRunPlugins()
	{
		if (defined('WELOQUENT_TEST_ENV')
			|| defined('WP_INSTALLING')
			|| (defined('WP_USE_THEMES') && WP_USE_THEMES === false)
            || (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST === true)
        )
		{
			return true;
		}

		return false;
	}

} 