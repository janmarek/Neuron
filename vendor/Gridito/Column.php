<?php

namespace Gridito;

use Nette\Application\Control;

/**
 * Grid column
 *
 * @author Jan Marek
 * @license MIT
 */
class Column extends Control {

	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var string */
	private $label;

	/** @var callback */
	private $cellRenderer = null;

	/** @var bool */
	private $sortable = false;

	/**
	 * @var string|null
	 * @persistent
	 */
	public $sorting = null;

	/** @var string */
	private $dateTimeFormat = "j.n.Y G:i";

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="getters & setters">

	/**
	 * Get label
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}


	/**
	 * Set label
	 * @param string $label
	 * @return Column
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}


	/**
	 * Get cell renderer
	 * @return callback
	 */
	public function getCellRenderer() {
		return $this->cellRenderer;
	}


	/**
	 * Set cell renderer
	 * @param callback $cellRenderer
	 * @return Column
	 */
	public function setCellRenderer($cellRenderer) {
		$this->cellRenderer = $cellRenderer;
		return $this;
	}


	/**
	 * Is sortable?
	 * @return bool
	 */
	public function isSortable() {
		return $this->sortable;
	}


	/**
	 * Set sortable
	 * @param bool $sortable
	 * @return Column
	 */
	public function setSortable($sortable) {
		$this->sortable = $sortable;
		return $this;
	}


	/**
	 * Get date/time format
	 * @return string
	 */
	public function getDateTimeFormat() {
		return $this->dateTimeFormat;
	}


	/**
	 * Set date/time format
	 * @param string $dateTimeFormat
	 * @return Column
	 */
	public function setDateTimeFormat($dateTimeFormat) {
		$this->dateTimeFormat = $dateTimeFormat;
		return $this;
	}

	/**
	 * Get grid
	 * @return Grid
	 */
	public function getGrid() {
		return $this->getParent()->getParent();
	}
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="signals & loadState">

	/**
	 * Handle sort
	 * @param string $type
	 */
	public function handleSort($type) {
		$this->sorting = $type;

		if ($this->presenter->isAjax()) {
			$this->loadSorting();
			$this->getGrid()->invalidateControl();
		} else {
			$this->redirect("this");
		}
	}


	/**
	 * Load state
	 * @param array $params
	 */
	public function loadState(array $params) {
		parent::loadState($params);
		$this->loadSorting();
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="rendering">

	/**
	 * Default cell renderer
	 * @param mixed $record
	 * @param Column $column
	 */
	public function defaultCellRenderer($record, $column) {
		$name = $column->getName();
		$value = $record->$name;

		// true/false
		if (is_bool($value)) {
			$icon = $value ? "check" : "closethick";
			echo '<span class="ui-icon ui-icon-' . $icon . '"></span>';
			
		// date
		} elseif ($value instanceof \DateTime) {
			echo $value->format($this->dateTimeFormat);

		// other
		} else {
			echo $value;
		}
	}


	/**
	 * Render cell
	 * @param mixed $record
	 */
	public function renderCell($record) {
		call_user_func($this->cellRenderer ?: array($this, "defaultCellRenderer"), $record, $this);

	}


	/**
	 * Render header cell
	 */
	public function renderHeaderCell() {
		$this->template->setFile(__DIR__ . "/templates/th.phtml")->render();
	}
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="helpers">

	/**
	 * Load sorting
	 */
	private function loadSorting() {
		if (!$this->sortable || !in_array($this->sorting, array("asc", "desc"))) return;

		foreach ($this->getParent()->getComponents() as $column) {
			if ($column !== $this) $column->sorting = null;
		}

		$this->getGrid()->getModel()->setSorting($this->getName(), $this->sorting);
	}

    // </editor-fold>

}