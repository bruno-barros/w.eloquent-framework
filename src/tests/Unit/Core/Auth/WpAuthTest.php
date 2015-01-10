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
	public function user_should_return_a_WP_User_only_once_if_is_logged()
	{

		\WP_Mock::wpFunction('wp_get_current_user', array(
			'times'  => 1,
			'return' => new \WP_User(),
		));

		assertInstanceOf('WP_User', $this->wpAuth->user());
		assertInstanceOf('WP_User', $this->wpAuth->user());
	}


	/**
	 * @test
	 */
	public function user_should_return_MULL_if_is_not_logged()
	{

		\WP_Mock::wpFunction('wp_get_current_user', array(
			'times'  => 1,
			'return' => null,
		));

		assertNull($this->wpAuth->user());
	}

	/**
	 * @test
	 */
	public function user_should_return_a_integer_if_is_logged()
	{

		\WP_Mock::wpFunction('wp_get_current_user', array(
			'times'  => 1,
			'return' => new \WP_User(),
		));

		assertEquals(1, $this->wpAuth->id());
		assertEquals(1, $this->wpAuth->id());
	}


	/**
	 * @test
	 */
	public function validate_should_return_false_if_credentials_are_not_valid()
	{
		\WP_Mock::wpFunction('wp_authenticate_username_password', [
			'times'  => 1,
			'return' => new \WP_Error('code', 'Message'),
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->validate([
			'user_login'    => 'foo',
			'user_password' => 'bar',
		]);

		assertFalse($isValid);
	}

	/**
	 * @test
	 */
	public function validate_should_return_true_if_credentials_are_valid()
	{
		\WP_Mock::wpFunction('wp_authenticate_username_password', [
			'times'  => 1,
			'return' => 'foo',
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->validate([
			'user_login'    => 'foo',
			'user_password' => 'bar',
		]);

		assertTrue($isValid);
	}

	/**
	 * @test
	 */
	public function attempt_should_return_boolean_if_login_argument_if_false()
	{
		// login will succeed
		\WP_Mock::wpFunction('wp_authenticate_username_password', [
			'times'  => 1,
			'return' => 'foo',
		]);

		// should not be touched
		\WP_Mock::wpFunction('wp_signon', [
			'times'  => 0,
			'return' => 'foo',
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->attempt([
			'user_login'    => 'foo',
			'user_password' => 'bar',
		], true, false);

		assertTrue($isValid);

		// now login will fail
		\WP_Mock::wpFunction('wp_authenticate_username_password', [
			'times'  => 1,
			'return' => new \WP_Error('code', 'Message'),
		]);

		$isValid = $wpAuth->attempt([
			'user_login'    => 'foo',
			'user_password' => 'bar',
		], true, false);

		assertFalse($isValid);
	}

	/**
	 * @test
	 */
	public function attempt_should_return_true_if_credentials_are_valid()
	{
		// should be touched
		\WP_Mock::wpFunction('wp_signon', [
			'times'  => 1,
			'return' => new \WP_User(),
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->attempt([
			'user_login'    => 'foo',
			'user_password' => 'bar',
		]);

		assertTrue($isValid);
	}


	/**
	 * @test
	 */
	public function attempt_should_return_false_if_credentials_are_not_valid()
	{
		// should be touched
		\WP_Mock::wpFunction('wp_signon', [
			'times'  => 1,
			'return' => new \WP_Error('foo', 'bar'),
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->attempt([
			'user_login'    => 'wrong',
			'user_password' => 'wrong',
		]);

		assertFalse($isValid);
	}

	/**
	 * @test
	 * @expectedException \Weloquent\Core\Auth\LoginFailedException
	 */
	public function login_should_return_throw_error_if_credentials_are_not_valid()
	{
		\WP_Mock::wpFunction('wp_signon', [
			'times'  => 1,
			'return' => new \WP_Error('foo', 'bar'),
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->login([
			'user_login'    => 'wrong',
			'user_password' => 'wrong',
		]);

	}

	/**
	 * @test
	 */
	public function login_should_return_true_if_credentials_are_valid()
	{
		\WP_Mock::wpFunction('wp_signon', [
			'times'  => 1,
			'return' => new \WP_User(),
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);

		$isValid = $wpAuth->login([
			'user_login'    => 'wrong',
			'user_password' => 'wrong',
		]);

		assertTrue($isValid);

	}

	/**
	 * @test
	 */
	public function can_should_return_false_if_capability_is_not_able()
	{
		\WP_Mock::wpFunction('current_user_can', [
			'times'  => 1,
			'return' => false
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());

		assertFalse($wpAuth->can('delete_user'));

	}

	/**
	 * @test
	 */
	public function can_should_return_false_if_all_of_the_capabilities_are_not_able()
	{
		\WP_Mock::wpFunction('current_user_can', [
			'times'  => 3,
			'return' => false
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());

		assertFalse($wpAuth->can(['delete_user', 'do_something_else', 'once_more']));
	}


	/**
	 * @test
	 */
	public function can_should_return_true_if_capability_is_able()
	{
		\WP_Mock::wpFunction('current_user_can', [
			'times'  => 1,
			'return' => true
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());

		assertTrue($wpAuth->can('delete_user'));

	}


	/**
	 * @test
	 */
	public function can_should_return_true_if_one_of_the_capabilities_are_able()
	{
		\WP_Mock::wpFunction('current_user_can', [
			'times'  => 1,
			'return' => true
		]);

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());

		assertTrue($wpAuth->can(['delete_user', 'do_something_else', 'once_more']));
	}

	/**
	 * @test
	 */
	public function is_should_return_true_if_user_has()
	{
		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());

		assertTrue($wpAuth->is('administrator'));
		assertTrue($wpAuth->is('Administrator'));
	}

	/**
	 * @test
	 */
	public function is_should_return_false_if_user_has_not()
	{
		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());

		assertFalse($wpAuth->is(['editor', 'subscriber']));
	}


	/**
	 * @test
	 */
	public function level_should_validate_against_user_levels()
	{

		$app['config']['auth.credentials.login']    = 'user_login';
		$app['config']['auth.credentials.password'] = 'user_password';

		$wpAuth = new WpAuth($app);
		$wpAuth->setUser(new \WP_User());// level 10

		assertFalse($wpAuth->level(11));
		assertTrue($wpAuth->level(10));
		assertTrue($wpAuth->level(9));
		assertTrue($wpAuth->level(5));
		assertTrue($wpAuth->level(1));
		assertFalse($wpAuth->level(1, '='));
		assertFalse($wpAuth->level(5, '<'));
		assertFalse($wpAuth->level(5, '<='));
		assertTrue($wpAuth->level(5, '>'));
		assertTrue($wpAuth->level(5, '>='));
	}

}