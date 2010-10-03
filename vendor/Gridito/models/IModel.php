<?php

namespace Gridito;

/**
 * Data model
 *
 * @author Jan Marek
 * @license MIT
 */
interface IModel extends \IteratorAggregate, \Countable {

	public function setupGrid(Grid $grid);

	public function processActionParam($param);

	public function setSorting($column, $type);

	public function setLimit($offset, $limit);

}