<?php
if (!defined('ROOT_PATH'))
{
	define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);

define('WELOQUENT_TEST_ENV', true);

// load dependencies
require_once ROOT_PATH . '/vendor/autoload.php';

// load phpunit helpers
require_once ROOT_PATH . '/vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

// load WP helpers
if (!class_exists('WP'))
{
	require_once __DIR__ . '/Support/class-wp.php';
}
if (!class_exists('WP_Error'))
{
	require_once __DIR__ . '/Support/class-wp-error.php';
}

// Fake post
require_once __DIR__ . '/Support/WP_Post.php';

