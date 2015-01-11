<?php namespace Weloquent\Core;

use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Application
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class Application extends \Illuminate\Foundation\Application
{

	/**
	 * The w.eloquent framework version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.1';




	/**
	 * Alias to get App instance
	 *
	 * @return mixed
	 */
	public function getInstance()
	{
		return $this;
	}


	/**
	 * Run the application and save headers.
	 * The response is up to WordPress
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request $request
	 * @return void
	 */
	public function run(SymfonyRequest $request = null)
	{
		/**
		 * On testing environment the WordPress helpers
		 * will not have context. So we stop here.
		 */
		if(defined('WELOQUENT_TEST_ENV') && WELOQUENT_TEST_ENV)
		{
			return;
		}

		$request = $request ?: $this['request'];

		$response = with($stack = $this->getStackedClient())->handle($request);

		// just set headers, but the content
		if(! is_admin())
		{
			$response->sendHeaders();
		}

		if ('cli' !== PHP_SAPI)
		{
			$response::closeOutputBuffers(0, true);
		}


		/**
		 * Save the session data until the application dies
		 */
		add_action('wp_footer', function () use ($stack, $request, $response)
		{
			$stack->terminate($request, $response);

			Session::save('wp_footer');

		});

	}

	/**
	 * Register the core class aliases in the container.
	 *
	 * @return void
	 */
	public function registerCoreContainerAliases()
	{
		$aliases = array(
			'app'                      => 'Weloquent\Core\Application',
			'artisan'                  => 'Weloquent\Core\Console\WelConsole',
			'auth'                     => 'Illuminate\Auth\AuthManager',
			'auth.reminder.repository' => 'Illuminate\Auth\Reminders\ReminderRepositoryInterface',
			'blade.compiler'           => 'Weloquent\Plugins\Blade\BladeCompiler',
			'cache'                    => 'Illuminate\Cache\CacheManager',
			'cache.store'              => 'Illuminate\Cache\Repository',
			'config'                   => 'Illuminate\Config\Repository',
			'cookie'                   => 'Illuminate\Cookie\CookieJar',
			'encrypter'                => 'Illuminate\Encryption\Encrypter',
			'db'                       => 'Illuminate\Database\DatabaseManager',
			'events'                   => 'Illuminate\Events\Dispatcher',
			'files'                    => 'Illuminate\Filesystem\Filesystem',
			'form'                     => 'Illuminate\Html\FormBuilder',
			'hash'                     => 'Illuminate\Hashing\HasherInterface',
			'html'                     => 'Illuminate\Html\HtmlBuilder',
			'translator'               => 'Illuminate\Translation\Translator',
			'log'                      => 'Illuminate\Log\Writer',
			'mailer'                   => 'Illuminate\Mail\Mailer',
			'paginator'                => 'Illuminate\Pagination\Factory',
			'auth.reminder'            => 'Illuminate\Auth\Reminders\PasswordBroker',
			'queue'                    => 'Illuminate\Queue\QueueManager',
			'redirect'                 => 'Illuminate\Routing\Redirector',
			'redis'                    => 'Illuminate\Redis\Database',
			'request'                  => 'Illuminate\Http\Request',
			'router'
			=> 'Weloquent\Core\Http\Router',
			'session'                  => 'Illuminate\Session\SessionManager',
			'session.store'            => 'Illuminate\Session\Store',
			'remote'                   => 'Illuminate\Remote\RemoteManager',
			'url'                      => 'Illuminate\Routing\UrlGenerator',
			'validator'                => 'Illuminate\Validation\Factory',
			'view'                     => 'Illuminate\View\Factory',
		);

		foreach ($aliases as $key => $alias)
		{
			$this->alias($key, $alias);
		}
	}

}