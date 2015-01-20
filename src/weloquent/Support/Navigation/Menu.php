<?php namespace Weloquent\Support\Navigation;

use Weloquent\Core\Application;
use Weloquent\Support\Navigation\Contracts\MenuInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Menu
 *
 * This is a wrapper for 'register_nav_menus' and 'wp_nav_menu'
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class Menu implements MenuInterface
{


	/**
	 * @var Application
	 */
	private $app;

	/**
	 * Registered menus
	 * @var array
	 */
	private $menusRegistered = [];


	/**
	 * The ID of the last updated menu
	 * @var string
	 */
	private $lastUpdated = '';

	/**
	 * Defaults configuration
	 * @var array
	 */
	private $defaults = array(
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => '',
		'menu_id'         => '',
		'echo'            => false,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
	);

	/**
	 * @param Application $app
	 */
	function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_nav_menu
	 * @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
	 *
	 * @param string $name The identifier (slug like)
	 * @param string $description
	 * @param array $args See Wordpress codex to get the options
	 *
	 * @return $this
	 */
	public function add($name = '', $description = '', array $args = [])
	{
		// register on wp
		$this->registerMenu($name, $description);

		// configure the output
		$this->configureTheOutputMenu($name, $args);

		return $this;
	}

	/**
	 * Append a string on the menu
	 *
	 * @param string $string
	 * @return $this
	 */
	public function after($string = '')
	{
		$lastUpdated = $this->lastUpdated;

		add_filter('wp_nav_menu_items', function ($items, $args) use ($lastUpdated, $string)
		{
			if ($args->theme_location == $lastUpdated)
			{
				$items .= $string;
			}

			return $items;

		}, 10, 2);

		return $this;
	}

	/**
	 * Append a string on the menu
	 *
	 * @param string $string
	 * @return $this
	 */
	public function before($string = '')
	{
		$lastUpdated = $this->lastUpdated;

		add_filter('wp_nav_menu_items', function ($items, $args) use ($lastUpdated, $string)
		{
			if ($args->theme_location == $lastUpdated)
			{
				$items = $string . $items;
			}
			return $items;

		}, 10, 2);

		return $this;
	}

	/**
	 * @param string $name The menu identifier
	 * @return mixed
	 */
	public function render($name)
	{
		if (!isset($this->menusRegistered[$name]))
		{
			throw new NotFoundResourceException("The menu [{$name}] could not be found.");
		}

		return wp_nav_menu($this->menusRegistered[$name]);
	}

	/**
	 * @param $name
	 * @param $description
	 */
	private function registerMenu($name, $description)
	{
		$this->menusRegistered[$name] = ['description' => $description];

		add_action('init', function () use ($name, $description)
		{

			register_nav_menus(array(

				$name => $description,

			));

		});
	}

	/**
	 * @param $name
	 * @param $args
	 */
	private function configureTheOutputMenu($name, $args)
	{
		// overwrite this
		$args['echo']           = false;
		$args['menu']           = $name;
		$args['theme_location'] = $name;

		$config = array_merge($this->defaults, (array)$args);

		$this->updateMenu($name, $config);
		//		$this->menusRegistered[$name] = $config;
	}

	/**
	 * Add or update attributes to menu
	 *
	 * @param $name
	 * @param array $args
	 */
	private function updateMenu($name, array $args)
	{
		if (isset($this->menusRegistered[$name]))
		{
			$this->menusRegistered[$name] = array_merge($this->menusRegistered[$name], $args);
			$this->lastUpdated            = $name;
		}
	}

} 