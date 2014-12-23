<?php  namespace Weloquent\Presenters;


/**
 * PagePresenter
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class PagePresenter extends PostPresenter{



	/**
	 * | -----------------------------------
	 * | Relationship presenters
	 * | -----------------------------------
	 */

	/**
	 * Return the parent page as PagePresenter instance
	 *
	 * @return \self
	 */
	public function presentParent()
	{
		if ((int)$this->post_parent === 0)
		{
			return null;
		}
		$parent = get_post($this->post_parent);

		return new self($parent, false);
	}

}