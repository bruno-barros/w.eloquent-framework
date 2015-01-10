<?php namespace Weloquent\Core\Auth;

use Illuminate\Events\Dispatcher;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Auth\UserProviderInterface;
use Weloquent\Core\Exceptions\WpException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

/**
 * WpAuth
 *
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class WpAuth implements UserProviderInterface, UserInterface, AuthInterface
{
	/**
	 * Application
	 * @var
	 */
	private $app;

	/**
	 * The currently authenticated user.
	 *
	 * @var \WP_User
	 */
	protected $user;

	/**
	 * @var string
	 */
	protected $loginField = 'user_login';

	/**
	 * @var string
	 */
	protected $passwordField = 'user_password';

	/**
	 * The event dispatcher instance.
	 *
	 * @var \Illuminate\Events\Dispatcher
	 */
	protected $events;

	/**
	 * Store WpException
	 * @var null|WpException
	 */
	protected $error = null;

	/**
	 * Indicates if the logout method has been called.
	 *
	 * @var bool
	 */
	protected $loggedOut = false;

	function __construct($app)
	{
		$this->app = $app;
	}


	/**
	 * Determine if the current user is authenticated.
	 *
	 * @return bool
	 */
	public function check()
	{
		return is_user_logged_in();
	}

	/**
	 * Determine if the current user is a guest.
	 *
	 * @return bool
	 */
	public function guest()
	{
		return !is_user_logged_in();
	}

	/**
	 * Get the currently authenticated user.
	 *
	 * @return \WP_User
	 */
	public function user()
	{
		if ($this->loggedOut)
		{
			return null;
		}

		if (!is_null($this->user))
		{
			return $this->user;
		}

		return $this->user = wp_get_current_user();
	}

	/**
	 * Get the ID for the currently authenticated user.
	 *
	 * @return int|null
	 */
	public function id()
	{
		if ($this->loggedOut)
		{
			return null;
		}

		if (!$this->user())
		{
			return null;
		}

		return $this->user()->ID;
	}


	/**
	 * Validate a user's credentials.
	 *
	 * @param  array $credentials
	 * @return bool
	 */
	public function validate(array $credentials = array())
	{
		$username = $this->getLoginCredential($credentials);

		$password = $this->getPasswordCredential($credentials);

		$user = wp_authenticate_username_password(null, $username, $password);

		if (is_wp_error($user))
		{
			return false;
		}

		return true;
	}


	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param  array $credentials
	 * @param  bool $remember
	 *
	 * @param  bool $login
	 * @param bool $secure
	 * @return bool
	 */
	public function attempt(array $credentials = array(), $remember = false, $login = true, $secure = false)
	{
		$this->fireAttemptEvent($credentials, $remember, $login);

		if (!$login)
		{
			return $this->validate($credentials);
		}

		try
		{
			$this->login($credentials, $remember, $secure);
		} catch (LoginFailedException $e)
		{
			$this->setError($e);

			return false;
		}

		return true;
	}

	/**
	 * Log a user into the application.
	 *
	 * @param array $credentials
	 * @param  bool $remember
	 * @param bool $secure
	 * @return bool|\WP_Error
	 * @throws LoginFailedException
	 */
	public function login($credentials = array(), $remember = false, $secure = false)
	{
		$creds = array();

		$creds[$this->getLoginField()] = $this->getLoginCredential($credentials);

		$creds[$this->getPasswordField()] = $this->getPasswordCredential($credentials);

		$creds['remember'] = $remember;

		$user = wp_signon($creds, $secure);

		/**
		 * throw exception encapsulation of WP_Error
		 */
		if (is_wp_error($user))
		{
			throw new LoginFailedException($user);
		}

		// If we have an event dispatcher instance set we will fire an event so that
		// any listeners will hook into the authentication events and run actions
		// based on the login and logout events fired from the guard instances.
		if (isset($this->events))
		{
			$this->events->fire('auth.login', array($user, $remember));
		}

		$this->setUser($user);

		return true;
	}

	/**
	 * Return the login value from the credentials
	 *
	 * @param array $credentials
	 * @return mixed
	 */
	protected function getLoginCredential($credentials = array())
	{
		$loginField = $this->app['config']['auth.credentials.login'];

		if (isset($credentials[$loginField]))
		{
			return $credentials[$loginField];
		}
		else if (Input::has($loginField))
		{
			return Input::get($loginField);
		}
		else if (isset($credentials['user_login']))
		{
			return $credentials['user_login'];
		}
		else if (isset($credentials['log']))
		{
			return $credentials['log'];
		}

		throw new InvalidArgumentException("Login field not found");

	}

	/**
	 * Return the password value from the credentials
	 *
	 * @param array $credentials
	 * @return mixed
	 */
	protected function getPasswordCredential($credentials = array())
	{
		$passField = $this->app['config']['auth.credentials.password'];

		if (isset($credentials[$passField]))
		{
			return $credentials[$passField];
		}
		else if (Input::has($passField))
		{
			return Input::get($passField);
		}
		else if (isset($credentials['user_password']))
		{
			return $credentials['user_password'];
		}
		else if (isset($credentials['pwd']))
		{
			return $credentials['pwd'];
		}

		throw new InvalidArgumentException("Password field not found");

	}

	/**
	 * Log the user out of the application.
	 *
	 * @return void
	 */
	public function logout()
	{
		$user = $this->user();

		if (isset($this->events))
		{
			$this->events->fire('auth.logout', array($user));
		}

		wp_logout();

		// Once we have fired the logout event we will clear the users out of memory
		// so they are no longer available as the user is no longer considered as
		// being signed into this application and should not be available here.
		$this->user = null;

		$this->loggedOut = true;
	}

	/**
	 * Log the given user ID into the application.
	 *
	 * @param  mixed $id
	 * @return \WP_User
	 * @throws UserNotFoundException
	 */
	public function loginUsingId($id)
	{
		$user = get_user_by('id', $id);

		if (is_wp_error($user))
		{
			throw new UserNotFoundException($user);
		}

		if ($this->user())
		{
			$this->logout();
		}

		wp_clear_auth_cookie();
		wp_set_current_user($user->ID);
		wp_set_auth_cookie($user->ID);

		return $user;

	}

	/**
	 * Checks if the user is a certain role,
	 * or is one of many roles if passed an array.
	 *
	 * @param string|array $roles
	 * @return bool
	 */

	public function is($roles)
	{
		if (!$this->user())
		{
			return false;
		}

		$userRoles = $this->getUser()->roles;

		foreach ((array)$roles as $role)
		{
			if (in_array(mb_strtolower($role), $userRoles)
				|| in_array($role, $userRoles)
			)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if the user is has a certain permission,
	 * or has one of many permissions if passed an array.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/current_user_can
	 * @param string|array $capability
	 * @param mixed $args
	 * @return bool
	 */
	public function can($capability, $args = null)
	{
		if (!$this->user())
		{
			return false;
		}

		$userCan = false;

		if (is_array($capability))
		{
			foreach ($capability as $cap)
			{
				// if only one capability is accepted
				if (current_user_can($cap, $args) === true)
				{
					$userCan = true;
					break;
				}
			}
		}
		else
		{
			$userCan = current_user_can($capability, $args);
		}

		return $userCan;
	}

	/**
	 * Each role can have a numerical level as well;
	 * this is useful if you want to check someone
	 * has a higher role than someone else.
	 *
	 * Deprecated by WordPress
	 *
	 * @param int $level
	 * @param string $operator Possibilities: < <= = > >=
	 * @return bool
	 */
	public function level($level, $operator = '>=')
	{
		if (!$this->user())
		{
			return false;
		}

		$userCaps = array_keys($this->getUser()->allcaps);

		foreach ($userCaps as $cap)
		{
			// get just the first, higher
			if (substr($cap, 0, 6) === 'level_')
			{
				return $this->checkWithOperator(substr($cap, 6), $level, $operator);
			}
		}

		return false;
	}


	/**
	 * @param $a
	 * @param $b
	 * @param string $operator
	 * @return bool
	 */
	private function checkWithOperator($a, $b, $operator = '>=')
	{
		switch ($operator)
		{
			case '>=':
				$result = ($a >= $b) ?: false;
				break;
			case '>':
				$result = ($a > $b) ?: false;
				break;
			case '<=':
				$result = ($a <= $b) ?: false;
				break;
			case '<':
				$result = ($a < $b) ?: false;
				break;
			default;
				$result = ($a == $b) ?: false;
		}

		return $result;
	}

	/**
	 * Fire the attempt event with the arguments.
	 *
	 * @param  array $credentials
	 * @param  bool $remember
	 * @param  bool $login
	 * @return void
	 */
	protected function fireAttemptEvent(array $credentials, $remember, $login)
	{
		if ($this->events)
		{
			$payload = array($credentials, $remember, $login);

			$this->events->fire('auth.attempt', $payload);
		}
	}

	/**
	 * user_login
	 * @return string
	 */
	public function getLoginField()
	{
		return $this->loginField;
	}

	/**
	 * @param string $loginField
	 * @return $this
	 */
	public function setLoginField($loginField)
	{
		$this->loginField = $loginField;

		return $this;
	}

	/**
	 * user_password
	 * @return string
	 */
	public function getPasswordField()
	{
		return $this->passwordField;
	}

	/**
	 * @param string $passwordField
	 * @return $this
	 */
	public function setPasswordField($passwordField)
	{
		$this->passwordField = $passwordField;

		return $this;
	}


	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed $identifier
	 * @return \WP_User|null
	 */
	public function retrieveById($identifier)
	{
		return new \WP_User($identifier);
	}

	/**
	 * Retrieve a user by by their unique identifier and "remember me" token.
	 *
	 * @param  mixed $identifier
	 * @param  string $token
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByToken($identifier, $token)
	{
		// TODO: Implement retrieveByToken() method.
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Auth\UserInterface $user
	 * @param  string $token
	 * @return void
	 */
	public function updateRememberToken(UserInterface $user, $token)
	{
		// TODO: Implement updateRememberToken() method.
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// TODO: Implement retrieveByCredentials() method.
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Auth\UserInterface $user
	 * @param  array $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials)
	{
		// TODO: Implement validateCredentials() method.
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return 'ID';
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->user()->data->user_pass;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		// TODO: Implement getRememberToken() method.
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		// TODO: Implement setRememberToken() method.
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		// TODO: Implement getRememberTokenName() method.
	}

	/**
	 * Return the currently cached user of the application.
	 *
	 * @return \WP_User|null
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set the current user of the application.
	 *
	 * @param \WP_User $user
	 */
	public function setUser(\WP_User $user)
	{
		$this->user = $user;

		$this->loggedOut = false;
	}


	/**
	 * Get the event dispatcher instance.
	 *
	 * @return \Illuminate\Events\Dispatcher
	 */
	public function getDispatcher()
	{
		return $this->events;
	}

	/**
	 * Set the event dispatcher instance.
	 *
	 * @param  \Illuminate\Events\Dispatcher
	 * @return void
	 */
	public function setDispatcher(Dispatcher $events)
	{
		$this->events = $events;
	}

	/**
	 * @return null|WpException
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @param null|WpException $error
	 */
	public function setError($error)
	{
		$this->error = $error;
	}
}