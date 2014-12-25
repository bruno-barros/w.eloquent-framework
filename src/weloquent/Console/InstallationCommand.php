<?php namespace Weloquent\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * InstallationCommand
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class InstallationCommand extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'installation:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Optimize WordPress installation.';
	/**
	 * @var Filesystem
	 */
	private $files;

	/**
	 * Create a new command instance.
	 *
	 * @return \Weloquent\Console\InstallationCommand
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct();
		$this->files = $files;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$rootPath = dirname(ABSPATH);

		$fromDir  = $rootPath . '/cms/wp-admin';
		$toDir    = $rootPath . '/wp-admin';
		$donne = $this->files->copyDirectory($fromDir, $toDir);

		//dump();
		$this->line('WordPress updated!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}