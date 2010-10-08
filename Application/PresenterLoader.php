<?php

namespace Neuron\Application;

use Nette\Application\InvalidPresenterException;
use Nette\String;
use Nette\Reflection\ClassReflection;

/**
 * Presenter loader with namespaces support
 *
 * @author Jan Marek
 */
class PresenterLoader implements \Nette\Application\IPresenterLoader
{
	/** @var array */
	private $namespaces = array("");

	/** @var array */
	private $cache = array();



	public function getNamespaces()
	{
		return $this->namespaces;
	}



	public function setNamespaces(array $namespaces)
	{
		$this->namespaces = $namespaces;
	}



	public function getPresenterClass(&$name)
	{
		if (isset($this->cache[$name])) {
			list($className, $name) = $this->cache[$name];
			return $className;
		}

		if (!is_string($name) || !String::match($name, "#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*$#")) {
			throw new InvalidPresenterException("Presenter name must be alphanumeric string, '$name' is invalid.");
		}

		$classNameBase = str_replace(':', 'Module\\', $name) . 'Presenter';

		$classNames = array_map(function ($namespace) use ($classNameBase) {
			return ($namespace ? $namespace . "\\" : "") . $classNameBase;
		}, $this->namespaces);

		foreach ($classNames as $className) {
			if (!class_exists($className)) {
				continue;
			}

			$reflection = new ClassReflection($className);

			if (!$reflection->implementsInterface('Nette\Application\IPresenter')) {
				throw new InvalidPresenterException("Cannot load presenter '$name', class '$className' is not Nette\\Application\\IPresenter implementor.");
			}

			if ($reflection->isAbstract()) {
				throw new InvalidPresenterException("Cannot load presenter '$name', class '$className' is abstract.");
			}

			return $className;
		}

		throw new InvalidPresenterException("Cannot load presenter $name, class " . implode(" nor ", $classNames) . " does not exist");
	}

}