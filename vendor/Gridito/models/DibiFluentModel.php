<?php

namespace Gridito;

use DibiFluent;

/**
 * DibiFluent model
 *
 * @author Jan Marek
 * @license MIT
 */
class DibiFluentModel extends AbstractModel
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var DibiFluent */
	private $fluent;

	/** @var string */
	private $rowClass;

	// </editor-fold>



	/**
	 * Constructor
	 * @param DibiFluent dibi fluent object
	 * @param string row class name
	 */
	public function __construct(DibiFluent $fluent, $rowClass = "DibiRow")
	{
		$this->fluent = $fluent;
		$this->rowClass = $rowClass;
	}



	/**
	 * Get iterator
	 */
	public function getIterator()
	{
		$fluent = clone $this->fluent;

		$fluent->limit($this->getLimit());
		$fluent->offset($this->getOffset());

		list($sortColumn, $sortType) = $this->getSorting();
		if ($sortColumn) {
			$fluent->orderBy("[$sortColumn] $sortType");
		}

		$res = $fluent->execute();

		return $res->setRowClass($this->rowClass)->getIterator();
	}



	/**
	 * Item count
	 * @return int
	 */
	public function count()
	{
		return $this->fluent->count();
	}

}