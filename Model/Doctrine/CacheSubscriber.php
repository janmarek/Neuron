<?php

namespace Neuron\Model\Doctrine;

use Doctrine\ORM\Events, Doctrine\ORM\Event;
use Nette\Environment;
use Nette\Caching\Cache;

/**
 * Cache subscriber
 *
 * @author Jan Marek
 */
class CacheSubscriber implements \Doctrine\Common\EventSubscriber
{
	public function getSubscribedEvents()
	{
		return array(Events::onFlush);
	}

	

	public function onFlush(Event\OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$tags = array();

		foreach ($uow->getScheduledEntityDeletions() as $entity) {
			$tags[] = get_class($entity);
			$tags[] = $entity->getCacheKey();
		}
		
		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			$tags[] = get_class($entity);
			$tags[] = $entity->getCacheKey();
		}

		Environment::getCache()->clean(array(
			Cache::TAGS => array_unique($tags)
		));
	}
}