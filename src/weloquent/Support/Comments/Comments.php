<?php namespace Weloquent\Support\Comments;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

/**
 * Comments
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class Comments
{

	protected $itemTemplate = 'partials.comments.item';

	function __construct()
	{
		if($tmpl = Config::get('comments.itemTemplate'))
		{
			$this->itemTemplate = $tmpl;
		}
	}

	public function item($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ('div' == $args['style'])
		{
			$tag       = 'div';
			$add_below = 'comment';
		}
		else
		{
			$tag       = 'li';
			$add_below = 'div-comment';
		}

		echo View::make($this->itemTemplate, compact('args', 'tag', 'add_below', 'comment', 'depth'))->render();

	}

}