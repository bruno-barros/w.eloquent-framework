<?php  namespace Weloquent\Core\Console;

use Illuminate\Console\Command;

/**
 * AssetsOptimizeCommand
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class AssetsOptimizeCommand extends Command{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'asset:optimize';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Update task runner with the assets to be";

	/**
	 * The asset publisher instance.
	 *
	 * @var \Illuminate\Foundation\AssetPublisher
	 */
	protected $assets;


}