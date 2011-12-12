<?php

/**
 * Core Boilerplate library namespace. This namespace contains all of the
 * fundamental components of Boilerplate, plus additional utilities that are
 * provided by default. Some of these default components have sub-namespaces if
 * they provide child objects.
 *
 * @package    Boilerplate
 * @subpackage Library
 */
namespace Boilerplate;

/**
 * Autoloader class
 *
 * This Autoloader class is an implementation of the "universal" autoloader for
 * PHP 5.3. It is able to load classes that use either:
 *
 *  * The technical interoperability standards for PHP 5.3 namespaces and
 *    class names (https://gist.github.com/1234504)
 *  * The PEAR naming convention for classes (http://pear.php.net).
 *
 * Classes from a sub-namespace or sub-hierarchy of PEAR classes can be looked
 * for in a list of locations to ease the vendoring of a sub-set of clsases for
 * large projects.
 *
 * @author     Fabien Potencier <fabien@symfony.com>
 * @package    Boilerplate
 * @subpackage Library
 */
class Autoloader
{
	/**
	 * Configured namespaces
	 *
	 * @var    array
	 */
	private $namespaces = array();

	/**
	 * Configured class prefixes
	 *
	 * @var    array
	 */
	private $prefixes = array();

	/**
	 * Directory(ies) used for fallback namespacing
	 *
	 * @var    array
	 */
	private $namespaceFallbacks = array();

	/**
	 * Directory(ies) used for fallback class prefixes
	 *
	 * @var    array
	 */
	private $prefixFallbacks = array();

	/**
	 * Gets the configured namespaces
	 *
	 * @return   array            A hash with namespaces as keys and directories as values
	 */
	public function getNamespaces()
	{
		return $this->namespaces;
	}

	/**
	 * Gets the configured class prefixes
	 *
	 * @return   array            A hash with class prefixes as keys and directories as values
	 */
	public function getPrefixes()
	{
		return $this->prefixes;
	}

	/**
	 * Gets the directory(ies) to use as a fallback for namespaces
	 *
	 * @return   array            An array of directories
	 */
	public function getNamespaceFallbacks()
	{
		return $this->namespaceFallbacks;
	}

	/**
	 * Gets the directory(ies) to use as a fallback for class prefixes.
	 *
	 * @return   array            An array of directories
	 */
	public function getPrefixFallbacks()
	{
		return $this->prefixFallbacks;
	}

	/**
	 * Registers the directory to use as a fallback for namespaces.
	 *
	 * @param    array            An array of directories
	 * @return   void             No value is returned
	 */
	public function registerNamespaceFallbacks(array $dirs)
	{
		$this->namespaceFallbacks = $dirs;
	}

	/**
	 * Registers the directory to use as a fallback for class prefixes.
	 *
	 * @param    array            An array of directories
	 * @return   void             No value is returned
	 */
	public function registerPrefixFallbacks(array $dirs)
	{
		$this->prefixFallbacks = $dirs;
	}

	/**
	 * Registers an array of namespaces
	 *
	 * @param    array            An array of namespaces (namespaces as keys and locations as values)
	 * @return   void             No value is returned
	 */
	public function registerNamespaces(array $namespaces)
	{
		foreach($namespaces as $namespace => $locations)
		{
			$this->namespaces[$namespace] = (array) $locations;
		}
	}

	/**
	 * Registers a namespace.
	 *
	 * @param    string           The namespace
	 * @param    string|array     The location(s) of the namespace
	 * @return   void             No value is returned
	 */
	public function registerNamespace($namespace, $paths)
	{
		$this->namespaces[$namespace] = (array) $paths;
	}

	/**
	 * Registers an array of classes using the PEAR naming convention
	 *
	 * @param    array            An array of classes (prefixes as keys and locations as values)
	 * @return   void             No value is returned
	 */
	public function registerPrefixes(array $classes)
	{
		foreach($classes as $prefix => $locations)
		{
			$this->prefixes[$prefix] = (array) $locations;
		}
	}

	/**
	 * Registers an array of classes using the PEAR naming convention
	 *
	 * @param    array            An array of classes (prefixes as keys and locations as values)
	 * @return   void             No value is returned
	 */
	public function registerPrefix($prefix, $paths)
	{
		$this->prefixes[$prefix] = (array) $paths;
	}

	/**
	 * Registers this instance as an autoloader
	 *
	 * @param    boolean          Whether to prepend the autoloader or not
	 * @return   void             No value is returned
	 */
	public function register($prepend = false)
	{
		\spl_autoload_register(array($this, 'loadClass'), true, $prepend);
	}

	/**
	 * Loads the given class or interface
	 *
	 * @param    string           The name of the class or interface
	 * @return   void             No value is returned
	 */
	public function loadClass($class)
	{
		if($file = $this->findFile($class))
		{
			require $file;
		}
	}

	/**
	 * Finds the path to the file where the class or interface is defined.
	 *
	 * @param    string           The name of the class or interface
	 * @return   string|null      The path if found, otherwise null
	 */
	public function findFile($class)
	{
		if('\\' == $class[0])
		{
			$class = \substr($class, 1);
		}

		if(false !== $pos = \strrpos($class, '\\'))
		{
			// namespaced class name
			$namespace = \substr($class, 0, $pos);

			foreach($this->namespaces as $ns => $dirs)
			{
				if(0 !== \strpos($namespace, $ns))
				{
					continue;
				}

				foreach($dirs as $dir)
				{
					$className = \substr($class, $pos + 1);
					$file = $dir.\DIRECTORY_SEPARATOR.\str_replace('\\', \DIRECTORY_SEPARATOR, $namespace).\DIRECTORY_SEPARATOR.\str_replace('_', \DIRECTORY_SEPARATOR, $className).'.php';

					if(\file_exists($file))
					{
						return $file;
					}
				}
			}

			foreach($this->namespaceFallbacks as $dir)
			{
				$file = $dir.\DIRECTORY_SEPARATOR.\str_replace('\\', \DIRECTORY_SEPARATOR, $class).'.php';

				if(\file_exists($file))
				{
					return $file;
				}
			}
		}
		else
		{
			// PEAR-like class name
			foreach($this->prefixes as $prefix => $dirs)
			{
				if(0 !== \strpos($class, $prefix))
				{
					continue;
				}

				foreach($dirs as $dir)
				{
					$file = $dir.\DIRECTORY_SEPARATOR.\str_replace('_', \DIRECTORY_SEPARATOR, $class).'.php';

					if(\file_exists($file))
					{
						return $file;
					}
				}
			}

			foreach($this->prefixFallbacks as $dir)
			{
				$file = $dir.\DIRECTORY_SEPARATOR.\str_replace('_', \DIRECTORY_SEPARATOR, $class).'.php';

				if(\file_exists($file))
				{
					return $file;
				}
			}
		}
	}
}

/* End of file Autoloader.php */