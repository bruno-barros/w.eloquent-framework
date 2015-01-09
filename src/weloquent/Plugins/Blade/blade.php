<?php  namespace Weloquent\Plugins\Blade;

/**
 * @wordpress-plugin
 * Plugin Name: Blade
 * Plugin URI: http://brunobarros.com/
 * Description: Blade wrapper for Wordpress templates
 * Version: 1.0.0
 * Author: Bruno Barros
 * Author URI: http://www.brunobarros.com/
 * License: GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       app-integration
 * Domain Path:       /languages
 */

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

/**
 * Blade
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class BladePlugin
{
	/**
	 * @var App
	 */
	private $app;

	/**
	 * @var array|mixed
	 */
	private $paths = array();

	/**
	 * @var array
	 */
	private $defaultFilters = [
		'index_template',
		'comments_template',
		'bp_template_include',// Listen for Buddypress include action
	];

	/**
	 * @var array
	 */
	private $defaultActions = [
		'template_include',
	];

	/**
	 * Constructor
	 */
	function __construct()
	{

		$this->app   = App::getFacadeApplication();
		$this->paths = require SRC_PATH . '/bootstrap/paths.php';

		$appActions = $this->app['config']['view.view_actions'];
		$appFilters = $this->app['config']['view.view_filters'];

		$viewAct = $appActions ?: $this->defaultActions;
		$viewFil = $appFilters ?: $this->defaultFilters;

		/**
		 * Add actions
		 */
		foreach((array)$viewAct as $action)
		{
			add_action($action, array($this, 'parse'));
		}

		/**
		 * Add filters
		 */
		foreach((array)$viewFil as $filter)
		{
			add_filter($filter, array($this, 'parse'));
		}

	}


	/**
	 * Return a new class instance.
	 * @return \Weloquent\Plugins\Blade\Blade { obj } class instance
	 */
	public static function make()
	{
		return new self();
	}


	/**
	 * Handle the compilation of the templates
	 *
	 * @param $template
	 */
	public function parse($template)
	{

		$viewsFromConfig = $this->app['config']->get('view.paths');

		$views = array_merge((array)$viewsFromConfig, (array)$this->paths['theme']);
		$cache = $this->paths['storage'] . '/views';

		$blade = new BladeAdapter($views, $cache);

		if (!$blade->getCompiler()->isExpired($template))
		{
			return $blade->getCompiler()->getCompiledPath($template);
		}

		$blade->getCompiler()->compile($template);

		return $blade->getCompiler()->getCompiledPath($template);

	}



}

/**
 * Bootstrap plugin
 */
BladePlugin::make();
