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

		$this->assertInstanceOf('Weloquent\Config\Environment', $env);

	}

	/**
	 * @test
	 */
	public function it_should_cleanup_path_string()
	{

		$env1 = new Environment('/some/path');
		$this->assertEquals('/some/path'.DS, $env1->getPath());

		$env2 = new Environment('/some/path/');
		$this->assertEquals('/some/path'.DS, $env2->getPath());

		$env3 = new Environment('some/path');
		$this->assertEquals('some/path'.DS, $env3->getPath());

		$env4 = new Environment('\some\path\\');
		$this->assertEquals('\some\path'.DS, $env4->getPath());

	}


}