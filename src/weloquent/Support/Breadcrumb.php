<?php namespace Weloquent\Support;

use Illuminate\Support\Collection;
use Weloquent\Presenters\PagePresenter;
use Weloquent\Presenters\PostPresenter;

/**
 * Breadcrumb
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class Breadcrumb
{

	/**
	 * @var PagePresenter
	 */
	protected $post;

	/**
	 * The title of the homepage. Default is site name.
	 * @var null
	 */
	protected $homeTitle = null;

	function __construct($wpPost = null)
	{
		global $post;

		$this->post = new PagePresenter((! $wpPost) ? $post : $wpPost);
	}


	/**
	 * Quick way to get the breadcrumb
	 *
	 * @return mixed
	 */
	public static function render()
	{
		$bc = new static;

		return $bc->get();
	}

	/**
	 * Return the breadcrumb as array
	 *
	 * @return Collection
	 */
	public function get()
	{

		$b = array();

		if (!is_front_page())
		{
			$b[] = array(
				'title' => ($this->homeTitle) ? $this->homeTitle : get_bloginfo('name'),
				'url'   => get_option('home')
			);

			if (is_category() || is_single())
			{
				if ($this->post->categories)
				{
					foreach ($this->post->categories as $c)
					{
						$b[] = array(
							'title' => $c->name,
							'url'   => $c->permalink
						);
					}
				}

				if (is_single())
				{
					$b[] = array(
						'title' => $this->post->title,
						'url'   => false
					);
				}

			}
			elseif (is_date())
			{
				$b[] = array(
					'title' => get_the_time('F \d\e Y'),
					'url'   => false
				);
			}
			elseif (is_page() && $this->post->post_parent)
			{
				for ($i = count($this->post->ancestors) - 1; $i >= 0; $i--)
				{
					$b[] = array(
						'id'    => $this->post->ancestors[$i],
						'title' => get_the_title($this->post->ancestors[$i]),
						'url'   => get_permalink($this->post->ancestors[$i])
					);
				}

				$b[] = array(
					'title' => $this->post->title,
					'url'   => false
				);

			}
			elseif (is_page())
			{
				$b[] = array(
					'title' => $this->post->title,
					'url'   => false
				);
			}
			elseif (is_404())
			{
				$b[] = array(
					'title' => "404",
					'url'   => false
				);
			}
		}
		else
		{
			$b[] = array(
				'title' => ($this->homeTitle) ? $this->homeTitle : get_bloginfo('name'),
				'url'   => false
			);
		}

		return Collection::make($b);
	}

}