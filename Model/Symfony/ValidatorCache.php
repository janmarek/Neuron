<?php

namespace Neuron\Model\Symfony;

use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Symfony validator - Nette cache
 *
 * @author Jan Marek
 */
class ValidatorCache implements \Symfony\Component\Validator\Mapping\Cache\CacheInterface
{
	private $cache;

	public function __construct($cache)
	{
		$this->cache = $cache;
	}


	public function has($class)
	{
		return isset($this->cache[$class]);
	}



	public function read($class)
	{
		return $this->cache[$class];
	}



	public function write(ClassMetadata $metadata)
	{
		$this->cache[$metadata->getClassName()] = $metadata;
	}

}