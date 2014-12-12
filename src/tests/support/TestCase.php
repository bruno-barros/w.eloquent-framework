<?php  namespace Weloquent\Tests\Support;


/**
 * TestCase
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class TestCase extends \PHPUnit_Framework_TestCase{


	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
		\Mockery::close();
	}

}