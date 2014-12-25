<?php namespace Weloquent\Providers;

use App\Commands\ExampleCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Tests\FilesystemTest;
use Weloquent\Console\InstallationCommand;
use Weloquent\Core\Console\Wel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\TailCommand;
use Weloquent\Core\Console\EnvironmentCommand;

class WelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('artisan', function($app)
		{
			return new Wel($app);
		});

		$this->app->bindShared('command.tail', function()
		{
			return new TailCommand;
		});

		$this->app->bindShared('command.environment', function()
		{
			return new EnvironmentCommand;
		});

		$this->app->bindShared('command.installation', function()
		{
			return new InstallationCommand(new Filesystem());
		});

		$this->commands('command.tail', 'command.environment', 'command.installation');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('artisan', 'command.environment', 'command.installation');
	}

}
