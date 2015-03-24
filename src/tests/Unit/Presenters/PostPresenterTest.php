<?php namespace Weloquent\Tests\Unit\Presenters;

use Weloquent\Presenters\PostPresenter;
use Weloquent\Tests\Support\TestCase;


/**
 * PostPresenterTest
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class PostPresenterTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();
	}

	private function fakePost()
	{
		return new \WP_Post();
	}

	/**
	 * @test
	 */
	public function is_instantiable()
	{
		$presenter = new PostPresenter($this->fakePost(), false);

		$this->assertInstanceOf('Weloquent\Presenters\Contracts\PresenterInterface', $presenter);
	}

	/**
	 * @test
	 */
	public function if_should_return_properties()
	{
		$presenter = new PostPresenter($this->fakePost(), false);

		$this->assertEquals($presenter->ID, 1);
		$this->assertEquals($presenter->post_title, 'Foo Bar');
	}

	/**
	 * @test
	 */
	public function it_should_return_presenter_methods()
	{
		$presenter = new PostPresenter($this->fakePost(), false);

		$this->assertEquals('foo-bar', $presenter->slug);
	}

	/**
	 * @test
	 */
	public function it_should_return_AuthorPresenter_instance()
	{
		$presenter = new PostPresenter($this->fakePost(), false);

		$authorPresenter = $presenter->author();

		$this->assertInstanceOf('Weloquent\Presenters\AuthorPresenter', $authorPresenter);
	}

	/**
	 * @test
	 */
	public function it_should_have_access_to_author_properties()
	{
		$presenter = new PostPresenter($this->fakePost(), false);

		$this->assertEquals(2, $presenter->author()->ID);
	}

}