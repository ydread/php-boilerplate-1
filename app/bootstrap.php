<?php

// Include functionality we cannot autoload
require_once '../vendors/boilerplate/src/Boilerplate/Autoloader.php';

$loader = new \Boilerplate\Autoloader();

// Register PSR-0 namespaces
$loader->registerNamespaces(array(
	'Boilerplate' => __DIR__.'/../vendors/boilerplate/src',
));

// Register PEAR class prefixes
$loader->registerPrefixes(array(
));

// Fallback PSR-0 namespaces
$loader->registerNamespaceFallbacks(array(
	__DIR__.'/src',
));

// Fallback PEAR class prefixes
$loader->registerPrefixFallbacks(array());

// Register the Autoloader to the SPL autoload stack
$loader->register();

/* End of file bootstrap.php */