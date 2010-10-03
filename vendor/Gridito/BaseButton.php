<?php

namespace Gridito;

use Nette\Application\PresenterComponent;
use Nette\Application\ForbiddenRequestException;
use Nette\Web\Html;

/**
 * Button base
 *
 * @author Jan Marek
 * @license MIT
 */
abstract class BaseButton extends PresenterComponent {

	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var string */
	private $label;

	/** @var callback */
	private $handler;

	/** @var string */
	private $icon;

	/** @var bool|callback */
	private $visible = true;

	/** @var string|callback */
	private $link = null;
	
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
	 * @return BaseButton
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}


	/**
	 * Get handler
	 * @return callback
	 */
	public function getHandler() {
		return $this->handler;
	}


	/**
	 * Set handler
	 * @param callback $handler
	 * @return BaseButton
	 */
	public function setHandler($handler) {
		$this->handler = $handler;
		return $this;
	}


	/**
	 * Get jQuery UI icon
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}


	/**
	 * Set jQuery UI icon
	 * @param string $icon
	 * @return BaseButton
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}


	/**
	 * Get visible
	 * @return bool|callback
	 */
	public function getVisible() {
		return $this->visible;
	}
	

	/**
	 * Is button visible
	 * @param mixed $row
	 * @return bool
	 */
	public function isVisible($row = null) {
		return is_bool($this->visible) ? $this->visible : call_user_func($this->visible, $row);
	}


	/**
	 * Set visible
	 * @param bool|callback $visible
	 * @return BaseButton
	 */
	public function setVisible($visible) {
		if (!is_bool($visible) && !is_callable($visible)) {
			throw new \InvalidArgumentException("Argument should be callable or boolean.");
		}
		
		$this->visible = $visible;
		return $this;
	}

	
	/**
	 * @return Grid
	 */
	public function getGrid() {
		return $this->getParent()->getParent();
	}



	/**
	 * Get link URL
	 * @return string|callback
	 */
	public function getLink()
	{
		return $this->link;
	}



	/**
	 * Set link URL
	 * @param string|callback link
	 * @return BaseButton
	 */
	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="signals">

	/**
	 * Handle click signal
	 * @param string $token security token
	 * @param mixed $pk
	 */
	public function handleClick($token, $pk = null) {
		$grid = $this->getGrid();

		if ($token !== $this->getGrid()->getSecurityToken()) {
			throw new ForbiddenRequestException("Security token does not match. Possible CSRF attack.");
		}

		call_user_func($this->handler, $grid->getModel()->processActionParam($pk));
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="rendering">

	/**
	 * Get button link
	 * @param mixed $row
	 * @return string
	 */
	protected function getButtonLink($row) {
		if ($this->link) {
			if (is_callable($this->link)) {
				return call_user_func($this->link, $row);
			}

			return $this->link;
		}

		$grid = $this->getGrid();

		$params["token"] = $grid->getSecurityToken();

		if ($row) {
			$params["pk"] = $row->{$grid->getPrimaryKey()};
		}

		return $this->link("click!", $params);
	}


	/**
	 * Create button element
	 * @param mixed $row
	 * @return Html
	 */
	protected function createButton($row = null) {
		$el = Html::el("a")
			->href($this->getButtonLink($row))
			->setText($this->label);

		if ($this->icon) {
			$el->icon("ui-icon-" . $this->icon);
		}

		return $el;
	}


	/**
	 * Render button
	 * @param mixed $row
	 */
	public function render($row = null) {		
		if ($this->isVisible($row)) {
			echo $this->createButton($row);
		}
	}

	// </editor-fold>

}