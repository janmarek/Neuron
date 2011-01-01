<?php

namespace Symfony\Component\Validator\Constraints;

use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Roman Sklenář, Jan Marek
 * @link https://gist.github.com/664454
 */
class UniqueValidator extends ConstraintValidator
{
	public function isValid($value, Constraint $constraint)
	{
		/* @var $context Symfony\Component\Validator\ValidationContext */
		$context = $this->context;
		$property = $context->getCurrentProperty();

		$em = \Nette\Environment::getService('Doctrine\ORM\EntityManager');
		$entity = $context->getRoot();

		$qb = $em->getRepository(get_class($entity))->createQueryBuilder('e');
		$qb->select('count(e.id)')->where("e.$property = ?1")->setParameter('1', $value);

		if ($entity->getId()) {
			$qb->andWhere('e.id <> ?2')->setParameter('2', $entity->getId());
		}

		$count = $qb->getQuery()->getSingleScalarResult();
		$valid = (int) $count === 0;

		if ($valid) {
			return true;
		} else {
			$this->setMessage($constraint->message, array('value' => $value));
			return false;
		}
	}
}
