<?php

namespace Neuron\Model\Tag;

/**
 * Tag service
 *
 * @author Jan Marek
 */
class Service extends \Neuron\Model\Service
{
	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Tag");
	}



	public function getFinder()
	{
		return new Finder($this);
	}



	public function getDictionary()
	{
		return $this->getFinder()->orderByName()->fetchPairs('id', 'name');
	}



	public function getUrlDictionary()
	{
		return $this->getFinder()->fetchPairs('id', 'url');
	}



	public function getUsedTags()
	{
		return $this->getFinder()->orderByName()->whereUsed()->getResult();
	}

}