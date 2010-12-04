<?php

namespace Neuron\Model\Page;

/**
 * Page finder
 *
 * @author Jan Marek
 */
class Finder extends \Neuron\Model\EntityFinder
{
	protected $alias = "p";



	public function whereAllowed()
	{
		$this->qb->andWhere("p.allowed = :allowed")->setParameter("allowed", true);
		return $this;
	}



	public function whereUrl($url)
	{
		$this->qb->andWhere("p.url = :url")->setParameter("url", $url);
		return $this;
	}

}