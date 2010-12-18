<?php

namespace Neuron\Model\Doctrine;

/**
 * EntityClassAliasesSubscriber
 *
 * @author Jan Marek
 */
class EntityClassAliasesSubscriber implements \Doctrine\Common\EventSubscriber
{
	private $aliases;



	public function __construct($aliases)
	{
		$this->aliases = $aliases;
	}



	public function getSubscribedEvents()
	{
		return array(\Doctrine\ORM\Events::loadClassMetadata);
	}



	public function loadClassMetadata(\Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

		foreach ($classMetadata->associationMappings as &$mapping) {
			if (isset($this->aliases[$mapping["targetEntity"]])) {
				$mapping["targetEntity"] = $this->aliases[$mapping["targetEntity"]];
			}
		}
    }

}
