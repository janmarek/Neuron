<?php

namespace Neuron\Presenter\FrontModule;

use Neuron\Form\LoginForm;
use Neuron\MenuControl;

/**
 * Front presenter
 *
 * @author Jan Marek
 */
abstract class FrontPresenter extends \Neuron\Application\BasePresenter
{
	/**
	 * Login form component factory
	 */
	protected function createComponentLoginForm()
	{
		return new LoginForm;
	}



	protected function createComponentMenu($name)
	{
		return new MenuControl($this, $name);
	}



	protected function createComponentClip($name)
	{
		return new \Neuron\Control\Clip($this, $name);
	}



	protected function createComponentJs()
	{
		return $this->getService("FrontJs");
	}



	protected function createComponentCss()
	{
		return $this->getService("FrontCss");
	}
}