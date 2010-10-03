<?php

namespace Neuron\Model\Doctrine;

use Doctrine\ORM\Events, Doctrine\ORM\Event;
use Nette\Environment;

/**
 * Symfony validation
 *
 * @author Jan Marek
 */
class ValidationSubscriber implements \Doctrine\Common\EventSubscriber
{
	public function getSubscribedEvents()
	{
		return array(Events::onFlush);
	}



	public function onFlush(Event\OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			$this->validateEntity($entity);
		}

		foreach ($uow->getScheduledEntityInsertions() as $entity) {
			$this->validateEntity($entity);
		}
	}



	private function validateEntity($entity) {
		$errors = Environment::getService("validator")->validate($entity);

		if (count($errors) > 0) {
			foreach ($errors as $violation) {
				throw new \Neuron\Model\ValidationException($violation->getMessage(), $violation->getPropertyPath());
			}
		}
	}
}