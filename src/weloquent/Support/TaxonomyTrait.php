<?php namespace Weloquent\Support;

/**
 * TaxonomyTrait
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
trait TaxonomyTrait {


	/**
	 * Return the post type slug if available
	 *
	 * @return bool|string
	 */
	public function getPostType()
	{
		global $wp_query;

		if(isset($wp_query->post) && $wp_query->post->post_type)
		{
			return $wp_query->post->post_type;
		}
		if (isset($wp_query->query) && isset($wp_query->query['post_type']))
		{
			return $wp_query->query['post_type'];
		}
		else if(isset($wp_query->query_vars) && isset($wp_query->query_vars['post_type']))
		{
			return $wp_query->query_vars['post_type'];
		}

		return false;
	}

	/**
	 * Return the taxonomy slug if available
	 *
	 * @return bool|mixed
	 */
	public function getTax()
	{
		global $wp_query;

		if (isset($wp_query->tax_query) && !empty($wp_query->tax_query))
		{
			return reset($wp_query->tax_query->queries)['taxonomy'];
		}

		return false;
	}

	/**
	 * Return the taxonomy term if available
	 *
	 * @return bool|mixed
	 */
	public function getTerm()
	{
		global $wp_query;

		if (isset($wp_query->tax_query) && !empty($wp_query->tax_query))
		{
			$first = reset($wp_query->tax_query->queries);
			return is_array($first) ? reset($first['terms']) : false;
		}

		return false;
	}

}