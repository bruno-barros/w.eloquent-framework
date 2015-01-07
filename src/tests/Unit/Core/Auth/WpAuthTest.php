<?php namespace Weloquent\Tests\Unit\Core\Auth;

use Mockery\Mock;
use Weloquent\Core\Auth\WpAuth;
use Weloquent\Tests\Support\TestCase;

/**
 * WpAuthTest
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class WpAuthTest extends TestCase
{

	private $app;

	private $wpAuth;

	public function setUp()
	{
		parent::setUp();

		$this->app = \Mockery::mock('Weloquent\Core\Application');

		$this->wpAuth = new WpAuth($this->app);
	}

	/**
	 * @test
	 */
	public function it_should_implement_UserInterface()
	{
		assertInstanceOf('Illuminate\Auth\UserProviderInterface', $this->wpAuth);
	}

	/**
	 * @test
	 */
	public function check_should_return_true_if_user_is_logged()
	{
		\WP_Mock::wpFunction('is_user_logged_in', array(
			'times'  => 1,
			'return' => true,
		));

		assertTrue($this->wpAuth->check());
	}


	/**
	 * @test
	 */
	public function check_should_return_false_if_user_is_not_logged()
	{
		\WP_Mock::wpFunction('is_user_logged_in', array(
			'times'  => 1,
			'return' => false,
		));

		assertFalse($this->wpAuth->check());
	}


	/**
	 * @test
	 */
	public function guest_should_return_false_if_user_is_logged()
	{
		\WP_Mock::wpFunction('is_user_logged_in', array(
			'times'  => 1,
			'return' => true,
		));

		assertFalse($this->wpAuth->guest());
	}

	/**
	 * @test
	 */
	public function guest_should_return_true_if_user_is_not_logged()
	{
		\WP_Mock::wpFunction('is_user_logged_in', array(
			'times'  => 1,
			'return' => false,
		));

		assertTrue($this->wpAuth->guest());
	}

	/**
	 * @test
	 */
	public function user_should_return_a_WP_User_if_is_logged()
	{

		\WP_Mock::wpFunction('wp_get_current_user', array(
			'times'  => 1,
			'return' => false,
		));
	}


}