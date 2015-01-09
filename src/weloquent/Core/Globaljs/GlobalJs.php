<?php namespace Weloquent\Core\Globaljs;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mockery\Test\Generator\StringManipulation\Pass\CallTypeHintPassTest;

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

	private $metas = [];

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
			$this->data  = Arr::add($this->data, $index, $data);
			$this->metas = Arr::add($this->metas, $index, ['admin' => $toAdmin]);
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
		if ($this->has($index))
		{
			Arr::forget($this->data, $index);
			Arr::forget($this->metas, $index);
		}

		return $this;
	}

	/**
	 * Remove all data
	 *
	 * @return $this
	 */
	public function removeAll()
	{
		$this->data  = [];
		$this->metas = [];

		return $this;
	}

	/**
	 * check if index already exists
	 *
	 * @param null $index
	 * @return bool
	 */
	public function has($index = null)
	{
		return Arr::has($this->data, $index);
	}

	/**
	 * Return the data as JSON
	 *
	 * @param bool $admin
	 * @return string|void
	 */
	public function toJson($admin = false)
	{
		$data = $this->prepareDataToJson($admin);

		return json_encode($data);
	}

	/**
	 * Return the data
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	private function prepareDataToJson($admin = false)
	{

		// apply admin filter
		$raw = Arr::where($this->getData(), function ($key, $value) use ($admin)
		{
			return $value['admin'] === $admin;
		});

		$new = [];

		$collection = Collection::make($raw);

		dd($collection->map(function ($item, $key)
		{
			return $item['value'];
		}));
	}

}