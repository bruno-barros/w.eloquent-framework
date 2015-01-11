<?php namespace Weloquent\Core\Console;

/**
 * ClearCompiledCommand
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class ClearCompiledCommand extends \Illuminate\Foundation\Console\ClearCompiledCommand
{


	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		if (file_exists($path = $this->laravel['path.storage'] . '/compiled.php'))
		{
			@unlink($path);
		}

		if (file_exists($path = $this->laravel['config']['app.manifest'] . '/services.json'))
		{
			@unlink($path);
		}
	}

}