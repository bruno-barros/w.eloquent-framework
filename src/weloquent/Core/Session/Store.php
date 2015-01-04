<?php namespace Weloquent\Core\Session;

/**
 * Store
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class Store extends \Illuminate\Session\Store
{


	/**
	 * {@inheritdoc}
	 */
	public function save($from = false)
	{

		$this->addBagDataToSession();

		/**
		 * Only clean flash data if is a page refresh, not redirection
		 */
		if ($from == 'wp_footer')
		{
			$this->ageFlashData();

		}

		$this->handler->write($this->getId(), serialize($this->attributes));

		$this->started = false;
	}

}