<?php
namespace Weloquent\Config;

class Environment
{
	/**
	 * Root path where files are located.
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * Environments locations.
	 * 
	 * @var array
	 */
	protected $locations = array();

	/**
	 * If is testing environment
	 * @var bool
	 */
	protected $isTesting = false;

	/**
	 * Init the Environment class.
	 * 
	 * @param string $path The root path where environments files are located.
	 * @param array $locations Environments locations: 'local' - 'production'... and hostnames.
	 */ 
	public function __construct($path, array $locations = array())
	{
		$this->setPath($path);
		$this->locations = $locations;
	}

	/**
	 * Find which environment we are.
	 * 
	 * @return string
	 */
	public function which()
	{
		if($this->isTesting())
		{
			$this->isTesting = true;
			return 'testing';
		}

		if(file_exists($this->getPath().'.env.local.php'))
		{
			return 'local';
		}

		return 'production';
	}

	/**
	 * Load the .env.{$location}.php file.
	 * 
	 * @param string $location
	 * @return array
	 */
	public function load($location)
	{
		if($this->isTesting())
		{
			$location = 'local';
		}

		if (file_exists($path = $this->getFile($location)))
		{
            return require_once($path);
		}

		return array();
	}

	/**
	 * Check required values.
	 * 
	 * @param array $required The required values to check.
	 * @param array $values
	 * @return bool
	 */
	public function check(array $required, array $values)
	{
		foreach ($required as $key)
		{
			if (!array_key_exists($key, $values))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Populate environment vars.
	 * 
	 * @param array $vars The loaded environments vars.
	 * @return void
	 */
	public function populate(array $vars)
	{
		foreach ($vars as $key => $value)
		{
			if (false === getenv($key))
			{
				$_ENV[$key] = $value;
				$_SERVER[$key] = $value;
				putenv("{$key}={$value}");
			}
		}
	}

	/**
	 * Return the .env file path.
	 * 
	 * @param string $location
	 * @return string
	 */
	protected function getFile($location)
	{
		if($location == 'production' && file_exists($envFile = $this->getPath().".env.php"))
		{
			return $envFile;
		}

		return $this->getPath().".env.{$location}.php";
	}


	/**
	 * Check for testing environment flag
	 *
	 * @return bool
	 */
	public function isTesting()
	{
		if(defined('WELOQUENT_TEST_ENV'))
		{
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set path with trailing slash
	 *
	 * @param string $path
	 */
	public function setPath($path)
	{
		$this->path = rtrim(rtrim($path, '/'), '\\').DS;
	}
}
