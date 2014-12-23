<?php  namespace Weloquent\Presenters;


/**
 * AuthorPresenter
 *
 * @link http://codex.wordpress.org/Template_Tags/the_author_meta
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class AuthorPresenter extends BasePresenter{


	/**
	 * Return a object with author information
	 *
	 * @return \stdClass
	 */
	public function presentInfos()
	{
		$a              = new \stdClass();
		$a->id          = $this->getId();
		$a->avatar      = $this->presentAvatar();
		$a->name        = $this->presentName();
		$a->email       = $this->presentEmail();
		$a->description = $this->presentDescription();
		$a->url         = $this->presentUrl();

		return $a;
	}

	/**
	 * Return the author ID
	 *
	 * @return int
	 */
	public function presentId()
	{
		return $this->getId();
	}

	/**
	 * Return the <img> with the author avatar
	 *
	 * @return string
	 */
	public function presentAvatar()
	{
		return $this->getAuthorAvatar();
	}

	/**
	 * Return the <img> with the author avatar
	 *
	 * @param int $size Img size
	 * @param string $default Alternative image if not exists
	 * @param string $alt Alt text of the image
	 * @return string
	 */
	public function getAuthorAvatar($size = 96, $default = '', $alt = false)
	{
		return get_avatar($this->presentEmail(), $size, $default, $alt);
	}

	/**
	 * Return the author url
	 *
	 * @return string
	 */
	public function presentUrl()
	{
		return esc_url(get_author_posts_url($this->getId()));
	}

	/**
	 * Return the name of the author
	 *
	 * @return string
	 */
	public function presentName()
	{
		return get_the_author_meta('display_name', $this->getId());
	}

	/**
	 * Return the e-mail of the author
	 *
	 * @return string
	 */
	public function presentEmail()
	{
		return get_the_author_meta('user_email', $this->getId());
	}

	/**
	 * Return the description of the author
	 *
	 * @return string
	 */
	public function presentDescription()
	{
		return get_the_author_meta('description', $this->getId());
	}

	/**
	 * Return the author ID
	 *
	 * @return mixed|string
	 */
	private function getId()
	{
		if ($this->isMainQuery)
		{
			return get_the_author_meta('ID');
		}
		else
		{
			return $this->post_author;
		}
	}

}