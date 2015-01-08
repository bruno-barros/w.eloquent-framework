<?php namespace Weloquent\Plugins\AppIntegration;

	/**
	 * The plugin bootstrap file
	 *
	 * This file is read by WordPress to generate the plugin information in the plugin
	 * Dashboard. This file also includes all of the dependencies used by the plugin,
	 * registers the activation and deactivation functions, and defines a function
	 * that starts the plugin.
	 *
	 *
	 * @wordpress-plugin
	 * Plugin Name: AppIntegration
	 * Plugin URI: http://brunobarros.com/
	 * Description: A framework for WordPress developers.
	 * Version: 1.0.0
	 * Author: Bruno Barros
	 * Author URI: http://www.brunobarros.com/
	 * License: GPLv2
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       app-integration
	 * Domain Path:       /languages
	 */

// If this file is called directly, abort.
use Weloquent\Plugins\AppIntegration\Includes\AppIntegration;
use Illuminate\Support\Facades\App;
use Weloquent\Plugins\AppIntegration\Includes\ConfigurationsAutoLoader;

if (!defined('WPINC'))
{
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
$plugin = new AppIntegration(App::getFacadeApplication());
$plugin->run();

add_action('setup_theme', function ()
{
	/**
	 * ----------------------------------------------------------------
	 * Bootstrap auto loading theme scripts on app/autoload folder
	 * ----------------------------------------------------------------
	 */
	ConfigurationsAutoLoader::setApp(App::getFacadeApplication())->add();
});
