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
		assertInstanceOf('Weloquent\Tests\Unit\Core\Globaljs', new GlobalJs());
	}

}