<?php

namespace Neuron;

/**
 * AdminUserBox
 */
class AdminUserBox extends BaseControl
{
	/**
	 * Render control
	 */
	public function render()
	{
		if (!$this->getUser()->isInRole("admin")) {
			throw new \Nette\Application\ForbiddenRequestException;
		}

		$this->template->setFile(__DIR__ . "/template.phtml")->render();
	}

}