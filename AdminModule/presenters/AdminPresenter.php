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



	protected function createComponentUserBox()
	{
		return new \Neuron\AdminUserBox;
	}



	protected function createComponentJs()
	{
		return $this->getService("AdminJs");
	}



	protected function createComponentCss()
	{
		return $this->getService("AdminCss");
	}
	
}