<?php namespace Weloquent\Core\Globaljs;

use Illuminate\Support\Arr;

/**
 * GlobalJs
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class GlobalJs
{

	/**
	 * Data to be converted in JSON on view
	 * @var array
	 */
	private $data = [];


	/**
	 * Append new data
	 *
	 * @param string $index Unique identifier
	 * @param null $data
	 * @param bool $toAdmin If show on admin environment
	 * @return $this
	 */
	public function add($index, $data = null, $toAdmin = false)
	{
		if (!$this->has($index))
		{
			$this->data = Arr::add($this->data, $index, ['value' => $data, 'admin' => $toAdmin]);
		}

		return $this;
	}

	/**
	 * Update existing index
	 *
	 * @param $index
	 * @param null $data
	 * @param null|bool $toAdmin
	 * @return $this
	 */
	public function update($index, $data = null, $toAdmin = null)
	{

		return $this;
	}

	/**
	 * Remove index
	 *
	 * @param $index
	 * @return $this
	 */
	public function remove($index)
	{

		return $this;
	}


	/**
	 * check if index already exists
	 *
	 * @param null $index
	 * @return bool
	 */
	private function has($index = null)
	{
		return array_has($this->data, $index);
	}

}