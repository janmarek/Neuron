<?php

namespace Neuron\Model\Menu;

/**
 * Menu Gridito model
 *
 * @author Jan Marek
 */
class GriditoModel extends \Gridito\AbstractModel
{
	/** @var MenuService */
	private $service;

	public function __construct($menuService)
	{
		$this->service = $menuService;
	}

	protected function _count()
	{
		return $this->service->count();
	}

	public function getItemByUniqueId($uniqueId)
	{
		return $this->service->find($uniqueId);
	}

	public function getItems()
	{
		return $this->service->fetchTreeAsArray();
	}

	public function getUniqueId($item)
	{
		return $item->getNode()->getId();
	}


}