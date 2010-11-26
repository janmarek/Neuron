<?php

namespace Neuron\Model\Page;

/**
 * Page finder
 *
 * @author Jan Marek
 */
class Finder extends \Neuron\Model\EntityFinder
{

	public function whereArticle()
	{
		$this->qb->andWhere("e.allowed = :allowed")->setParameter("allowed", true);
		return $this;
	}


	public function whereUrl($url)
	{
		$this->qb->andWhere("e.url = :url")->setParameter("url", $url);
		return $this;
	}
	
}