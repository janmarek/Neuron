<?php

namespace Gridito;

/**
 * Window button
 *
 * @author Jan Marek
 * @license MIT
 */
class WindowButton extends BaseButton {

	/**
	 * Handle click signal
	 * @param string $token security token
	 * @param mixed $pk primary key
	 */
	public function handleClick($token, $pk = null) {
		ob_start();
		parent::handleClick($token, $pk);
		$output = ob_get_clean();

		if ($this->getPresenter()->isAjax()) {
			echo $output;
			$this->getPresenter()->terminate();
		} else {
			$this->getGrid()->getTemplate()->windowLabel = $this->getLabel();
			$this->getGrid()->getTemplate()->windowOutput = $output;
		}
	}


	/**
	 * Create button element
	 * @param mixed $row
	 * @return \Nette\Web\Html
	 */
	protected function createButton($row = null) {
		$el = parent::createButton($row);
		$el->onClick = "gridito.loadWindow(this.href, " . json_encode($this->getLabel()) . ", event)";
		return $el;
	}

}