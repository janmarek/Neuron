<?php

namespace Neuron;

use Nette\Environment, Nette\String;
use Nette\NeonParser;
use Nette\Reflection\ClassReflection;

/**
 * Service loader
 *
 * @author Jan Marek
 */
class ServiceLoader
{
	public function loadNeonConfigFiles(\Nette\Context $context, array $configFiles)
	{
		$parser = new NeonParser;

		foreach ($configFiles as $file) {
			$config = $parser->parse(file_get_contents($file));
			$this->loadConfig($context, $config);
		}
	}



	public function loadConfig($context, $config)
	{
		foreach ($config as $serviceName => $serviceConfig) {
			$options = null;
			$singleton = isset($serviceConfig["singleton"]) ? (bool) $serviceConfig["singleton"] : true;

			if (isset($serviceConfig["arguments"])) {
				$service = array($this, "universalFactory");

				if (isset($serviceConfig["class"])) {
					$options["class"] = $serviceConfig["class"];
				}

				if (isset($serviceConfig["factory"])) {
					$options["factory"] = $serviceConfig["factory"];
				}
				
				$options["arguments"] = $serviceConfig["arguments"];

			} elseif (isset($serviceConfig["factory"])) {
				$service = $serviceConfig["factory"];

			} elseif (isset($serviceConfig["class"])) {
				$service = $serviceConfig["class"];
			}

			$context->addService($serviceName, $service, $singleton, $options);
		}
	}


	
	public function universalFactory($options)
	{
		if (isset($options["arguments"])) {
			$arguments = array_map(function ($arg) {
				if (String::startsWith($arg, "%")) {
					return Environment::getService(substr($arg, 1));
				} elseif (String::startsWith($arg, "$$")) {
					return Environment::getConfig(substr($arg, 2));
				} elseif (String::startsWith($arg, "$")) {
					return Environment::getVariable(substr($arg, 1));
				} else {
					return $arg;
				}
			}, (array) $options["arguments"]);
		} else {
			$arguments = array();
		}

		if (isset($options["class"])) {
			return ClassReflection::from($options["class"])->newInstanceArgs($arguments);
		}

		if (isset($options["factory"])) {
			return call_user_func_array($options["factory"], $arguments);
		}
	}
}