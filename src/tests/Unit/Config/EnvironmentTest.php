<?php  namespace Weloquent\Tests\Unit\Config;

use Weloquent\Config\Environment;
use Weloquent\Tests\Support\TestCase;

/**
 * EnvironmentTest
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class EnvironmentTest extends TestCase{

	/**
	 * @test
	 */
	public function it_should_be_instantiable()
	{
		$env = new Environment('/some/path');

		assertInstanceOf('Weloquent\Config\Environment', $env);
	}


}