<?php
if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
}

define('IS_TEST', true);

$autoload = require_once ROOT_PATH . '/src/bootstrap/start.php';

require_once ROOT_PATH . '/vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

if (!class_exists('WP'))
{
	require_once __DIR__ . '/Support/class-wp.php';
}

if (!class_exists('WP_Error'))
{
	require_once __DIR__ . '/Support/class-wp-error.php';
}

