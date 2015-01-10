<?php  namespace Weloquent\Core\Auth;


/**
 * AuthInterface
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
interface AuthInterface {


	/**
	 * Checks if the user is a certain role, or is one of many roles if passed an array.
	 *
	 * @param string|array $roles
	 * @return bool
	 */
	public function is($roles);

	/**
	 * Checks if the user is has a certain permission,
	 * or has one of many permissions if passed an array.
	 *
	 * @param string|array $permissions
	 * @return bool
	 */
	public function can($permissions);

	/**
	 * Each role can have a numerical level as well;
	 * this is useful if you want to check someone
	 * has a higher role than someone else.
	 *
	 * @param int $level
	 * @param string $operator
	 */
	public function level($level, $operator = '>=');

}