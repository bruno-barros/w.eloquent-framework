<?php namespace Weloquent\Tests\Unit\Core\Http;

use Exception;
use Illuminate\Container\Container;
use Weloquent\Core\Application;
use Weloquent\Core\Exceptions\AjaxException;
use Weloquent\Core\Http\Ajax;
use Weloquent\Tests\Support\TestCase;

class MyFakeAjaxRunner{
	public function run()
	{
		return 'runner';
	}
}

/**
 * AjaxTest
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class AjaxTest extends TestCase
{


	private $ajax;

	public function setUp()
	{
		parent::setUp();
		$this->ajax = new Ajax(new Application());
	}

	/**
	 * @test
	 */
	public function it_should_instantiable()
	{
		assertInstanceOf('Weloquent\Core\Http\Ajax', new Ajax(new Application()));
	}

	/**
	 * @test
	 * @expectedException \Weloquent\Core\Exceptions\AjaxException
	 */
	public function it_should_throw_exception()
	{
		$this->ajax->run(1, 'both', 'foo');
	}


	/**
	 * @test
	 * @expectedException \Weloquent\Core\Exceptions\AjaxException
	 */
	public function it_should_throw_exception_with_non_existing_class()
	{
		$this->ajax->run('my_hook', 'both', 'foo');
	}


	/**
	 * @test
	 */
	public function it_should_not_throw_error_passing_closure()
	{
		\WP_Mock::wpFunction('add_action');

		$this->ajax->run('my_action', 'no', function(){

			return 'foo';

		});
	}


	/**
	 * @test
	 */
	public function it_should_not_throw_error_passing_a_class()
	{
		\WP_Mock::wpFunction('add_action');

		$this->ajax->run('my_action', 'no', 'Weloquent\Tests\Unit\Core\Http\MyFakeAjaxRunner');
	}


}