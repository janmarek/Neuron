<?php

namespace Neuron;

use Nette\Environment, Nette\String;
use Nette\NeonParser;
use Nette\Reflection\ClassReflection;
use Nette\IContext;

/**
 * Service loader
 *
 * @author Jan Marek
 */
class ServiceLoader
{
	public function loadNeonConfigFiles(IContext $context, array $configFiles)
	{
		$parser = new NeonParser;

		foreach ($configFiles as $file) {
			$config = $parser->parse(file_get_contents($file));
			$this->loadConfig($context, $config);
		}
	}



	public function loadConfig(IContext $context, $config)
	{
		foreach ($config as $serviceName => $serviceConfig) {
			if ($context->hasService($serviceName)) {
				$context->removeService($serviceName);
			}

			$options = null;
			$singleton = isset($serviceConfig["singleton"]) ? (bool) $serviceConfig["singleton"] : true;

			if (isset($serviceConfig["arguments"]) || isset($serviceConfig["callMethods"])) {
				$service = array($this, "universalFactory");

				if (isset($serviceConfig["class"])) {
					$options["class"] = $serviceConfig["class"];
				}

				if (isset($serviceConfig["factory"])) {
					$options["factory"] = $serviceConfig["factory"];
				}

				if (isset($serviceConfig["arguments"])) {
					$options["arguments"] = $serviceConfig["arguments"];
				}

				if (isset($serviceConfig["callMethods"])) {
					$options["callMethods"] = $serviceConfig["callMethods"];
				}

				$options["context"] = $context;

			} elseif (isset($serviceConfig["factory"])) {
				$service = $serviceConfig["factory"];

			} elseif (isset($serviceConfig["class"])) {
				$service = $serviceConfig["class"];
			}

			$context->addService($serviceName, $service, $singleton, $options);
		}
	}



	public function processArguments($args, IContext $context)
	{
		return array_map(function ($arg) use ($context) {
			if (!is_string($arg)) {
				return $arg;
			} elseif (String::startsWith($arg, "%")) {
				return $context->getService(substr($arg, 1));
			} elseif (String::startsWith($arg, "$$")) {
				return Environment::getConfig(substr($arg, 2));
			} elseif (String::startsWith($arg, "$")) {
				return Environment::getVariable(substr($arg, 1));
			} else {
				return $arg;
			}
		}, $args);
	}



	public function universalFactory($options)
	{
		$arguments = isset($options["arguments"]) ? $this->processArguments($options["arguments"], $options["context"]) : array();

		if (isset($options["class"])) {
			if (!empty($arguments)) {
				$object = ClassReflection::from($options["class"])->newInstanceArgs($arguments);
			} else {
				$class = $options["class"];
				$object = new $class;
			}
		}

		if (isset($options["factory"])) {
			$object = call_user_func_array($options["factory"], $arguments);
		}

		if (isset($options["callMethods"])) {
			foreach ($options["callMethods"] as $method => $args) {
				call_user_func_array(array($object, $method), $this->processArguments($args, $options["context"]));
			}
		}

		return $object;
	}
}