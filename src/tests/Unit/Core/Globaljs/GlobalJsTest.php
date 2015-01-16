<?php namespace Weloquent\Tests\Unit\Core\Globaljs;

use Weloquent\Core\Application;
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

//		\Mockery::mock('Weloquent\Core\Globaljs\GlobalJs')
//			->shouldReceive('generateNonce')->once()->andReturnNull();
//
//		\Mockery::mock('Weloquent\Core\Globaljs\GlobalJs')
//			->shouldReceive('generateAjaxUrl')->once()->andReturnNull();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiable()
	{
		assertInstanceOf('Weloquent\Core\Globaljs\GlobalJs', new GlobalJs(new Application()));
	}

	/**
	 * @test
	 */
	public function it_should_add_an_single_item()
	{
		$gjs = new GlobalJs(new Application());
		$gjs->add('foo', 'bar');
		$data = $gjs->getData();

		assertInternalType('array', $data);
		assertEquals(1, count($data));
		assertEquals('bar', $data['foo']);
	}


	/**
	 * @test
	 */
	public function it_should_add_an_multilevel_item()
	{
		$gjs = new GlobalJs(new Application());
		$gjs->add('foo.baz', 'bar');
		$gjs->add('foo.bazin', new \stdClass());
		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('bar', $data['foo']['baz']);
		assertInternalType('object', $data['foo']['bazin']);
	}

	/**
	 * @test
	 */
	public function it_should_remove_an_single_item()
	{
		$gjs = new GlobalJs(new Application());
		$gjs->add('sin', 'gle');
		$gjs->add('foo.baz', 'bar');

		$gjs->remove('sin');

		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('bar', $data['foo']['baz']);
	}

	/**
	 * @test
	 */
	public function it_should_remove_an_multilevel_item()
	{
		$gjs = new GlobalJs(new Application());
		$gjs->add('sin', 'gle');
		$gjs->add('foo.baz', 'bar');

		$gjs->remove('foo');

		$data = $gjs->getData();

		assertEquals(1, count($data));
		assertEquals('gle', $data['sin']);
	}

	/**
	 * @test
	 */
	public function complex_multilevel_data()
	{

		$gjs = new GlobalJs(new Application());
		$gjs->add('root', 'val1')
			->add('root.child', 'val2')
			->add('root.son', 'val3')
			->add('root.child.grandchild', 'val4')
			->remove('foo');

		$data = $gjs->getData();


		assertEquals(1, count($data));
		assertInternalType('array', $data['root']);
		assertInternalType('array', $data['root']['child']);
		assertEquals('val3', $data['root']['son']);
		assertEquals('val4', $data['root']['child']['grandchild']);
	}




}