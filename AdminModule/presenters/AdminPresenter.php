<?php

namespace Neuron\Presenter\AdminModule;

abstract class AdminPresenter extends \Neuron\Application\BasePresenter
{
	public function startup()
	{
		parent::startup();

		// user authentication
		if (!$this->getUser()->isInRole("admin")) {
			if ($this->getUser()->getLogoutReason() === \Nette\Web\User::INACTIVITY) {
				$this->flashMessage('You have been logged out due to inactivity. Please login again.');
			}

			throw new \Nette\Application\ForbiddenRequestException;
		}
	}



	protected function createComponentJs()
	{
		return $this->getService("adminJs");
	}



	protected function createComponentCss()
	{
		return $this->getService("adminCss");
	}
}