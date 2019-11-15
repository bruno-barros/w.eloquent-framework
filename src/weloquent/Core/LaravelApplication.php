<?php
// If this file is called directly, abort.
if (!defined('WPINC'))
{
	die;
}

/*
|--------------------------------------------------------------------------
| Check Extensions
|--------------------------------------------------------------------------
|
| Laravel requires a few extensions to function. Here we will check the
| loaded extensions to make sure they are present. If not we'll just
| bail from here. Otherwise, Composer will crazily fall back code.
|
*/
/*
if ( ! extension_loaded('mcrypt'))
{
	echo 'Mcrypt PHP extension required.'.PHP_EOL;

	exit(1);
}
*/

/*
|--------------------------------------------------------------------------
| Setup Patchwork UTF-8 Handling
|--------------------------------------------------------------------------
|
| The Patchwork library provides solid handling of UTF-8 strings as well
| as provides replacements for all mb_* and iconv type functions that
| are not available by default in PHP. We'll setup this stuff here.
|
*/
/*
Patchwork\Utf8\Bootup::initMbstring();
*/
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

use Weloquent\Core\Application;

$app = new \Weloquent\Core\Application;

/**
 * -----------------------------------------------------
 * Set my custom request class
 * -----------------------------------------------------
 */
Application::requestClass('Weloquent\Core\Http\Request');

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
*/

$objEnv     = new Weloquent\Config\Environment(dirname(ABSPATH));
$env        = $objEnv->which();
$app['env'] = $env;
/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/
$app->bindInstallPaths(require SRC_PATH . '/bootstrap/paths.php');

/*
|--------------------------------------------------------------------------
| Bind The Application In The Container
|--------------------------------------------------------------------------
|
| This may look strange, but we actually want to bind the app into itself
| in case we need to Facade test an application. This will allow us to
| resolve the "app" key out of this container for this app's facade.
|
*/

$app->instance('app', $app);

/*
|--------------------------------------------------------------------------
| Check For The Test Environment
|--------------------------------------------------------------------------
|
| If the "unitTesting" variable is set, it means we are running the unit
| tests for the application and should override this environment here
| so we use the right configuration. The flag gets set by TestCase.
|
*/

if (isset($unitTesting))
{
	$app['env'] = $env = $testEnvironment;
}

/*
|--------------------------------------------------------------------------
| Load The Illuminate Facades
|--------------------------------------------------------------------------
|
| The facades provide a terser static interface over the various parts
| of the application, allowing their methods to be accessed through
| a mixtures of magic methods and facade derivatives. It's slick.
|
*/
use Illuminate\Support\Facades\Facade;

Facade::clearResolvedInstances();

Facade::setFacadeApplication($app);
/*
|--------------------------------------------------------------------------
| Register Facade Aliases To Full Classes
|--------------------------------------------------------------------------
|
| By default, we use short keys in the container for each of the core
| pieces of the framework. Here we will register the aliases for a
| list of all of the fully qualified class names making DI easy.
|
*/
$app->registerCoreContainerAliases();


/*
|--------------------------------------------------------------------------
| Register The Configuration Repository
|--------------------------------------------------------------------------
|
| The configuration repository is used to lazily load in the options for
| this application from the configuration files. The files are easily
| separated by their concerns so they do not become really crowded.
|
*/
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\FileLoader;

$app->instance('config', $config = new Config(

	new FileLoader(new Filesystem, $app['path'] . '/config'), $env

));


/*
|--------------------------------------------------------------------------
| Register Application Exception Handling
|--------------------------------------------------------------------------
|
| We will go ahead and register the application exception handling here
| which will provide a great output of exception details and a stack
| trace in the case of exceptions while an application is running.
|
*/

$app->startExceptionHandling();

if ($env != 'testing') ini_set('display_errors', 'Off');

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| Here we will set the default timezone for PHP. PHP is notoriously mean
| if the timezone is not explicitly set. This will be used by each of
| the PHP date and date-time functions throughout the application.
|
*/

$config = $app['config']['app'];

date_default_timezone_set($config['timezone']);

/*
|--------------------------------------------------------------------------
| Register The Alias Loader
|--------------------------------------------------------------------------
|
| The alias loader is responsible for lazy loading the class aliases setup
| for the application. We will only register it if the "config" service
| is bound in the application since it contains the alias definitions.
|
*/

$aliases = $config['aliases'];

use Illuminate\Foundation\AliasLoader;

AliasLoader::getInstance($aliases)->register();

/*
|--------------------------------------------------------------------------
| Enable HTTP Method Override
|--------------------------------------------------------------------------
|
| Next we will tell the request class to allow HTTP method overriding
| since we use this to simulate PUT and DELETE requests from forms
| as they are not currently supported by plain HTML form setups.
|
*/
use Weloquent\Core\Http\Request;

Request::enableHttpMethodParameterOverride();

/*
|--------------------------------------------------------------------------
| Register The Core Service Providers
|--------------------------------------------------------------------------
|
| The Illuminate core service providers register all of the core pieces
| of the Illuminate framework including session, caching, encryption
| and more. It's simply a convenient wrapper for the registration.
|
*/

$providers = $config['providers'];

$app->getProviderRepository()->load($app, $providers);

$app->booted(function () use ($app, $env)
{

	/**
	 * --------------------------------------------------------------------------
	 * After load the application
	 * Load the logs and errors handlers
	 * --------------------------------------------------------------------------
	 *
	 * The start scripts gives this application the opportunity to override
	 * any of the existing IoC bindings, as well as register its own new
	 * bindings for things like repositories, etc. We'll load it here.
	 */
	$path = $app['path'].DS.'config'.DS.'logs.php';

	if (file_exists($path))
	{
		require $path;
	}
});

$app->run();

return $app;
