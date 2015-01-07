<?php namespace Weloquent\Tests\Unit\Core\Http;

use Weloquent\Core\Http\Redirector;
use Weloquent\Tests\Support\TestCase;

/**
 * RedirectorTest
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class RedirectorTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiable()
	{
		$urlGenerator = \Mockery::mock('\Illuminate\Routing\UrlGenerator');
		assertInstanceOf('Weloquent\Core\Http\Redirector', new Redirector($urlGenerator));
	}

}