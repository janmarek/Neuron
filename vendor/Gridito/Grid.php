<?php

namespace Gridito;

use Nette\Application\Control;
use Nette\ComponentContainer;
use Nette\Environment;
use Nette\Paginator;

/**
 * Grid
 *
 * @author Jan Marek
 * @license MIT
 */
class Grid extends Control {

	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var IModel */
	private $model;

	/** @var string */
	private $primaryKey = "id";

	/** @var Paginator */
	private $paginator;

	/** @var int */
	protected $defaultItemsPerPage = 20;

	/**
	 * @var int
	 * @persistent
	 */
	public $page = 1;

	/** @var string */
	private $ajaxClass = "ajax";

	/** @var int */
	private $toolbarButtonId = 0;

	/** @var int */
	private $actionButtonId = 0;

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="constructor">

	public function __construct() {
		// intentionally without parameters
		parent::__construct();
		$this->addComponent(new ComponentContainer, "toolbar");
		$this->addComponent(new ComponentContainer, "actions");
		$this->addComponent(new ComponentContainer, "columns");

	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="getters & setters">

	/**
	 * Get model
	 * @return IModel
	 */
	public function getModel() {
		return $this->model;
	}


	/**
	 * Set model
	 * @param IModel $model
	 * @return Grid
	 */
	public function setModel(IModel $model) {
		$model->setupGrid($this);
		$this->getPaginator()->setItemCount(count($model));
		$this->model = $model;
		return $this;
	}


	/**
	 * Get primary key name
	 * @return string
	 */
	public function getPrimaryKey() {
		return $this->primaryKey;
	}


	/**
	 * Set primary key name
	 * @param string $primaryKey
	 * @return Grid
	 */
	public function setPrimaryKey($primaryKey) {
		$this->primaryKey = $primaryKey;
		return $this;
	}


	/**
	 * Get items per page
	 * @return int
	 */
	public function getItemsPerPage() {
		return $this->getPaginator()->getItemsPerPage();
		return $this;
	}


	/**
	 * Set items per page
	 * @param int $itemsPerPage
	 * @return Grid
	 */
	public function setItemsPerPage($itemsPerPage) {
		$this->getPaginator()->setItemsPerPage($itemsPerPage);
		return $this;
	}


	/**
	 * Get ajax class
	 * @return string
	 */
	public function getAjaxClass() {
		return $this->ajaxClass;
	}


	/**
	 * Set ajax class
	 * @param string $ajaxClass
	 * @return Grid
	 */
	public function setAjaxClass($ajaxClass) {
		$this->ajaxClass = $ajaxClass;
		return $this;
	}


	/**
	 * Get paginator
	 * @return \Nette\Paginator
	 */
	public function getPaginator() {
		if (!$this->paginator) {
			$this->paginator = new Paginator;
			$this->paginator->setItemsPerPage($this->defaultItemsPerPage);
		}

		return $this->paginator;
	}


	/**
	 * Get security token
	 * @return string
	 */
	public function getSecurityToken() {
		$session = Environment::getSession(__CLASS__ . "-" . __METHOD__);

		if (empty($session->securityToken)) {
			$session->securityToken = md5(uniqid(mt_rand(), true));
		}

		return $session->securityToken;
	}


	/**
	 * Has toolbar
	 * @return bool
	 */
	public function hasToolbar() {
		return count($this["toolbar"]->getComponents()) > 0;
	}


	/**
	 * Has actions
	 * @return bool
	 */
	public function hasActions() {
		return count($this["actions"]->getComponents()) > 0;
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="signals & loadState">

	/**
	 * Handle change page signal
	 * @param int $page
	 */
	public function handleChangePage($page) {
		$this->setPage($page);

		if ($this->presenter->isAjax()) {
			$this->invalidateControl();
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
		$this->setPage(isset($params["page"]) ? $params["page"] : 1);
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="rendering">

	/**
	 * Render grid
	 */
	public function render() {
		$this->template->render();
	}
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="component factories">

	/**
	 * Add column
	 * @param string $name
	 * @param string $label
	 * @param callback $renderer
	 * @return Column
	 */
	public function addColumn($name, $label, $renderer = null) {
		$column = new Column($this["columns"], $name);
		$column->setLabel($label);
		if ($renderer) $column->setCellRenderer($renderer);
		return $column;
	}


	/**
	 * Add action button
	 * @param string $label button name
	 * @param callback $handler
	 * @param string $icon jQuery UI icon
	 * @return Button
	 */
	public function addButton($label, $handler, $icon = null) {
		$button = $this->createButton("\Gridito\Button", $label, $handler, $icon);
		$this["actions"]->addComponent($button, ++$this->actionButtonId);
		return $button;
	}


	/**
	 * Add action window button
	 * @param string $label button name
	 * @param callback $handler
	 * @param string $icon jQuery UI icon
	 * @return WindowButton
	 */
	public function addWindowButton($label, $handler, $icon = null) {
		$button = $this->createButton("\Gridito\WindowButton", $label, $handler, $icon);
		$this["actions"]->addComponent($button, ++$this->actionButtonId);
		return $button;
	}


	/**
	 * Add action button to toolbar
	 * @param string $label button name
	 * @param callback $handler
	 * @param string $icon jQuery UI icon
	 * @return Button
	 */
	public function addToolbarButton($label, $handler, $icon = null) {
		$button = $this->createButton("\Gridito\Button", $label, $handler, $icon);
		$this["toolbar"]->addComponent($button, ++$this->toolbarButtonId);
		return $button;
	}


	/**
	 * Add window button to toolbar
	 * @param string $label button name
	 * @param callback $handler
	 * @param string $icon jQuery UI icon
	 * @return WindowButton
	 */
	public function addToolbarWindowButton($label, $handler, $icon = null) {
		$button = $this->createButton("\Gridito\WindowButton", $label, $handler, $icon);
		$this["toolbar"]->addComponent($button, ++$this->toolbarButtonId);
		return $button;
	}

	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="helpers">

	/**
	 * Create template
	 * @return Template
	 */
	protected function createTemplate() {
		return parent::createTemplate()->setFile(__DIR__ . "/templates/grid.phtml");
	}


	/**
	 * Set page
	 * @param int $page
	 */
	private function setPage($page) {
		$paginator = $this->getPaginator();
		$paginator->setPage($page);
		$this->model->setLimit($paginator->getOffset(), $paginator->getLength());
	}


	/**
	 * Create button
	 * @param string $class button class name
	 * @param string $label
	 * @param callback $handler
	 * @param string $icon
	 * @return BaseButton
	 */
	private function createButton($class, $label, $handler, $icon = null) {
		$button = new $class;
		$button->setLabel($label);
		$button->setHandler($handler);
		if ($icon) $button->setIcon($icon);
		return $button;
	}

	// </editor-fold>
	
}