<?php

namespace Neuron\Model\User;

/**
 * User finder
 *
 * @author Jan Marek
 */
class Finder extends \Neuron\Model\EntityFinder
{
	public function whereUsername($username)
	{
		$this->qb->andWhere("$this->alias.username = :username")->setParameter("username", $username);
		return $this;
	}

}