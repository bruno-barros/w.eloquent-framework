<?php  namespace Weloquent\Providers;

use Weloquent\Core\Auth\WpAuth;

/**
 * AuthServiceProvider
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$driver = $this->app['config']['auth.driver'];

		if($driver === 'wordpress')
		{
			$this->app->bindShared('auth', function($app)
			{
				// Once the authentication service has actually been requested by the developer
				// we will set a variable in the application indicating such. This helps us
				// know that we need to set any queued cookies in the after event later.
				$app['auth.loaded'] = true;

				$auth = new WpAuth($app);

				$auth->setDispatcher($app['events']);

				return $auth;
			});
		}
		else
		{
			parent::register();
		}
	}
}