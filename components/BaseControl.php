<?php

namespace Neuron;

/**
 * Base control
 */
class BaseControl extends \Nette\Application\Control
{
	public function __construct(\Nette\IComponentContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->init();
	}



	protected function init()
	{

	}



	/**
	 * Get context
	 * @return \Nette\Context
	 */
	public function getContext()
	{
		return $this->getPresenter()->getContext();
	}



	public function getService($name)
	{
		return $this->getContext()->getService($name);
	}

	

	/**
	 * @return \Nette\Web\User
	 */
	public function getUser()
	{
		return $this->getService("Nette\Web\IUser");
	}

}