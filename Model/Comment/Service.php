<?php

namespace Neuron\Model\Comment;

/**
 * Comment service
 *
 * @author Jan Marek
 */
class Service extends \Neuron\Model\Service
{
	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Comment");
	}



	public function getFinder()
	{
		return new Finder($this);
	}



	public function addComment($group, array $data)
	{
		if ($group->isLocked()) {
			throw new LockedGroupException('You cannot add comment to a locked group.');
		}

		$em = $this->getEntityManager();
		$comment = new Comment($data);
		$em->persist($comment);
		$group->addComment($comment);
		$em->flush();
	}

}