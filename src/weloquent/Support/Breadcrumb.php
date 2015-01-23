<?php namespace Weloquent\Support;

use Illuminate\Support\Collection;
use Weloquent\Presenters\PagePresenter;

/**
 * Breadcrumb
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class Breadcrumb
{

	/**
	 * Post types excluded to show link
	 * @var array
	 */
	protected $excludedPostTypes = ['page', 'post'];

	/**
	 * @var PagePresenter
	 */
	protected $post;

	/**
	 * @var string
	 */
	protected $dateFormat = 'F Y';

	/**
	 * The title of the homepage. Default is site name.
	 * @var null
	 */
	protected $homeTitle = null;

	function __construct($wpPost = null)
	{
		global $post;

		$this->post = new PagePresenter((!$wpPost) ? $post : $wpPost);
	}


	/**
	 * Quick way to get the breadcrumb
	 *
	 * @return mixed
	 */
	public static function make()
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

		$b = [];

		if (!is_front_page())
		{
			// home
			$b[] = [
				'title' => ($this->homeTitle) ? $this->homeTitle : get_bloginfo('name'),
				'url'   => get_option('home')
			];

			// if is custom post type
			$postType = $this->post->postType;
			if (!in_array($postType->name, $this->excludedPostTypes) && !is_category())
			{
				$b[] = ['title' => $postType->label, 'url' => $postType->permalink];
			}

			if (is_category())
			{
				if ($c = get_category(get_query_var('cat')))
				{
					$b[] = ['title' => $c->name, 'url' => false];
				}

			}
			else if (is_single())
			{
				if($categories = $this->post->categories)
				{
					foreach ($categories as $c)
					{
						$b[] = ['title' => $c->name, 'url' => $c->permalink];
					}
				}
				$b[] = ['title' => $this->post->title, 'url' => false];

			}
			elseif (is_date())
			{
				$b[] = ['title' => get_the_time($this->dateFormat), 'url' => false];
			}
			elseif (is_page() && $this->post->post_parent)
			{
				for ($i = count($this->post->ancestors) - 1; $i >= 0; $i--)
				{
					$b[] = [
						'title' => get_the_title($this->post->ancestors[$i]),
						'url'   => get_permalink($this->post->ancestors[$i])
					];
				}

				$b[] = ['title' => $this->post->title, 'url' => false];

			}
			elseif (is_page())
			{
				$b[] = ['title' => $this->post->title, 'url' => false];
			}
			else if (is_tag())
			{
				$b[] = ['title' => single_tag_title('', false), 'url' => false];
			}
			else if(is_author())
			{
				$b[] = ['title' => sprintf(__('Posts by %s'), get_the_author()), 'url' => false];
			}
			else if (is_search())
			{
				$b[] = [
					'title' => sprintf(__('Search Results %1$s %2$s'), ':', '"' . esc_html(get_search_query()) . '"'),
					'url'   => false
				];
			}
			elseif (is_404())
			{
				$b[] = ['title' => __('Page not found'), 'url' => false];
			}
		}
		else
		{
			$b[] = [
				'title' => ($this->homeTitle) ? $this->homeTitle : get_bloginfo('name'),
				'url'   => false
			];
		}

		return Collection::make($b);
	}

}