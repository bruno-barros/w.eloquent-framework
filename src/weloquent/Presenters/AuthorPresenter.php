<?php  namespace Weloquent\Presenters;


/**
 * AuthorPresenter
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class AuthorPresenter extends BasePresenter{


	/**
	 * Return the author ID
	 *
	 * @return int
	 */
	public function presentId()
	{
		if ($this->isMainQuery)
		{
			return get_the_author_meta('ID');
		}
		else
		{
			return $this->post_author;
		}
	}

}