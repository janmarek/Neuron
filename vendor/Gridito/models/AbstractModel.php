<?php

namespace Gridito;

/**
 * Abstract Gridito model
 *
 * @author Jan Marek
 * @license MIT
 */
abstract class AbstractModel implements IModel
{
	/** @var array */
	private $limit;

	/** @var array */
	private $offset;

	/** @var array */
	private $sorting = array(null, null);

	/** @var string */
	private $primaryKey;



	/**
	 * Process action parameter
	 * @param mixed param
	 * @return mixed
	 */
	public function processActionParam($param)
	{
		return $param;
	}



	public function setLimit($limit)
	{
		$this->limit = $limit;
	}



	public function getLimit()
	{
		return $this->limit;
	}



	public function getOffset()
	{
		return $this->offset;
	}



	public function setOffset($offset)
	{
		$this->offset = $offset;
	}



	/**
	 * Set sorting
	 * @param string column
	 * @param string asc or desc
	 */
	public function setSorting($column, $type)
	{
		return $this->sorting = array($column, $type);
	}



	public function getSorting()
	{
		return $this->sorting;
	}



	public function setPrimaryKey($name)
	{
		$this->primaryKey = $name;
	}



	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}



	public function setupGrid(Grid $grid)
	{
		if (isset($this->primaryKey)) {
			$grid->setPrimaryKey($this->primaryKey);
		}
	}
}