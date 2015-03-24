<?php namespace Weloquent\Tests\Unit\Core\Exceptions;

use Weloquent\Core\Exceptions\WpException;
use Weloquent\Tests\Support\TestCase;

/**
 * WpExceptionTest
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class WpExceptionTest extends TestCase
{

	private $wpError;

	public function setUp()
	{
		parent::setUp();

		$this->wpError = new \WP_Error('wp_code_foo', '<strong>ERROR</strong>: Wp message foo', [1, 2, 3]);
	}

	/**
	 * @test
	 */
	public function it_should_get_an_object()
	{
		$wpe = new WpException("exception message", 999);

		$this->assertInternalType('object', $wpe);
	}

	/**
	 * @test
	 */
	public function it_should_have_access_to_exception_methods()
	{
		$wpe = new WpException("exception message", 999);

		$this->assertEquals('exception message', $wpe->getMessage());
		$this->assertEquals(999, $wpe->getCode());
	}

	/**
	 * @test
	 */
	public function it_should_get_information_from_WP_Error()
	{
		$wpe = new WpException($this->wpError, 222);

		$this->assertEquals('Wp message foo', $wpe->getMessage());
		$this->assertEquals(222, $wpe->getCode());
		$this->assertEquals('wp_code_foo', $wpe->getWpCode());
		$this->assertEquals([1, 2, 3], $wpe->getData());
	}


	/**
	 * @test
	 */
	public function it_should_clean_html_messages()
	{
		$error = new \WP_Error('wp_code_foo', '<strong>ERROR</strong>: Wp message foo <a href="#">link</a>');
		$wpe = new WpException($error);

		$this->assertEquals('Wp message foo <a href="#">link</a>', $wpe->getMessage());


		$error2 = new \WP_Error('wp_code_foo', '<strong>ALERT</strong>: <strong>Message</strong> <a href="#">link</a>');
		$wpe2 = new WpException($error2);

		$this->assertEquals('<strong>Message</strong> <a href="#">link</a>', $wpe2->getMessage());

	}

}