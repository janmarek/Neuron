<?php

namespace Gridito;

use DibiFluent;

/**
 * DibiFluent model
 *
 * @author Jan Marek
 * @license MIT
 */
class DibiFluentModel extends \Nette\Object implements IModel
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
		return $this->fluent->execute()->setRowClass($this->rowClass)->getIterator();
	}



	/**
	 * Process action parameter
	 * @param mixed param
	 * @return mixed
	 */
	public function processActionParam($param)
	{
		return $param;
	}



	/**
	 * Setup grid after model connect
	 * @param Grid grid
	 */
	public function setupGrid(Grid $grid)
	{
		
	}



	/**
	 * Set sorting
	 * @param string column
	 * @param string asc or desc
	 */
	public function setSorting($column, $type)
	{
		$this->fluent->removeClause("orderBy")->orderBy("[$column] $type");
	}



	/**
	 * Item count
	 * @return int
	 */
	public function count()
	{
		return $this->fluent->count();
	}



	/**
	 * Set limit
	 * @param int offset
	 * @param int limit
	 */
	public function setLimit($offset, $limit)
	{
		$this->fluent->removeClause("offset")->offset($offset);
		$this->fluent->removeClause("limit")->limit($limit);
	}

}