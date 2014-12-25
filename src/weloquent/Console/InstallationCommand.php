<?php  namespace Weloquent\Console;

use Illuminate\Console\Command;

/**
 * InstallationCommand
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2014 Bruno Barros
 */
class InstallationCommand extends Command{

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
	 * Create a new command instance.
	 *
	 * @return \Weloquent\Console\InstallationCommand
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->line("Do some stuff here!!!");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}