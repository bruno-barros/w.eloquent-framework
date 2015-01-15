<?php namespace Weloquent\Core\Console;

/**
 * OptimizeCommand
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class OptimizeCommand extends \Illuminate\Foundation\Console\OptimizeCommand
{


	/**
	 * Generate the compiled class file.
	 *
	 * @return void
	 */
	protected function compileClasses()
	{
		$this->registerClassPreloaderCommand();

		$outputPath = $this->laravel['path.storage'] . '/compiled.php';

		$this->callSilent('compile', array(
			'--config'         => implode(',', $this->getClassFiles()),
			'--output'         => $outputPath,
			'--strip_comments' => 1,
		));
	}

}