<?php

// Include functionality we cannot autoloader
require_once '../vendors/boilerplate/lib/Boilerplate/Autoloader.php';

$loader = new \Boilerplate\Autoloader();

$loader->registerNamespaces(array(
	'Assetic'          => __DIR__.'/../vendors/assetic/src',
	'Boilerplate'      => __DIR__.'/../vendors/boilerplate/lib',
	'Cryptonite'       => __DIR__.'/../vendors/cryptonite/lib',
	'Doctrine\\Common' => __DIR__.'/../vendors/doctrine-common/lib',
	'Doctrine\\DBAL'   => __DIR__.'/../vendors/doctrine-dbal/lib',
	'Doctrine'         => __DIR__.'/../vendors/doctrine/lib',
	'Monolog'          => __DIR__.'/../vendors/monolog/src',
	'Metadata'         => __DIR__.'/../vendors/metadata/src',
));

$loader->registerPrefixes(array(
	'Cache_'           => __DIR__.'/../vendors/cache-lite/Lite',
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
    'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
));

// Now, let's register the application's library
$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../app/lib',
));

$loader->register();

// Swiftmailer needs a special autoloader to allow the lazy-loading of its
// init file (which is expensive)
if(\is_dir(__DIR__.'/../vendors/swiftmailer'))
{
	require_once __DIR__.'/../vendor/swiftmailer/lib/classes/Swift.php';
	Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/lib/swift_init.php');
}

/* End of file bootstrap.php */