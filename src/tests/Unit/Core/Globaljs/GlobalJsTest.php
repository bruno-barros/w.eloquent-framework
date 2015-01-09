<?php namespace Weloquent\Tests\Unit\Core\Globaljs;

use Weloquent\Core\Globaljs\GlobalJs;
use Weloquent\Tests\Support\TestCase;

/**
 * GlobalJsTest
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class GlobalJsTest extends TestCase{

	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiable()
	{
		assertInstanceOf('Weloquent\Core\Globaljs\GlobalJs', new GlobalJs());
	}

	/**
	 * @test
	 */
	public function it_should_add_an_single_item()
	{
		$gjs = new GlobalJs();
		$gjs->add('foo', 'bar');
		$data = $gjs->getData();

		assertInternalType('array', $data);
		assertEquals(1, count($data));
		assertEquals('bar', $data['foo']['value']);
	}


	/**
	 * @test
	 */
	public function it_should_add_an_multilevel_item()
	{
		$gjs = new GlobalJs();
		$gjs->add('foo.baz', 'bar');
		$gjs->add('foo.bazin', new \stdClass());
		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('bar', $data['foo']['baz']['value']);
		assertInternalType('object', $data['foo']['bazin']['value']);
	}

	/**
	 * @test
	 */
	public function it_should_remove_an_single_item()
	{
		$gjs = new GlobalJs();
		$gjs->add('sin', 'gle');
		$gjs->add('foo.baz', 'bar');

		$gjs->remove('sin');

		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('bar', $data['foo']['baz']['value']);
	}

	/**
	 * @test
	 */
	public function it_should_remove_an_multilevel_item()
	{
		$gjs = new GlobalJs();
		$gjs->add('sin', 'gle');
		$gjs->add('foo.baz', 'bar');

		$gjs->remove('foo');

		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('gle', $data['sin']['value']);
	}

	/**
	 * @test
	 */
	public function complex_multilevel_data()
	{

		$gjs = new GlobalJs();
		$gjs->add('root', 'val1')
			->add('root.child', 'val2')
			->add('root.son', 'val3')
			->add('root.child.grandchild', 'val4')
			->remove('foo');

		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('val1', $data['root']['value']);
		assertEquals('val2', $data['root']['child']['value']);
		assertEquals('val3', $data['root']['son']['value']);
		assertEquals('val4', $data['root']['child']['grandchild']['value']);
	}

}