<?php

namespace Neuron\Application;

use Nette\Environment;
use WebLoader, cssmin, JSMin;
use Neuron\Form\LoginForm;

/**
 * @property-read \Nette\Context $context
 */
abstract class BasePresenter extends \Nette\Application\Presenter
{
	/**
	 * Get context
	 * @return \Nette\Context
	 */
	public function getContext()
	{
		return Environment::getContext();
	}



	public function getService($name)
	{
		return $this->getContext()->getService($name);
	}



	public function afterRender()
	{
		if (!$this->getSession()->isStarted()) {
			$this->getSession()->start();
		}
	}



	/**
	 * @return Nette\Templates\Template
	 */
	public function createTemplate()
	{
		$template = parent::createTemplate();
		$template->registerHelperLoader(array($this->getService("TemplateHelperLoader"), "getHelper"));
		return $template;
	}

	

	public function handleLogOut()
	{
		$this->getUser()->logout();
		$this->flashMessage("Byl jste úspěšně odhlášen.");
		$this->redirect(":Front:Homepage:");
	}



	public function formatTemplateFiles($presenter, $view)
	{
		$appDir = Environment::getVariable('appDir');
		$path = '/' . str_replace(':', 'Module/', $presenter);
		$pathP = substr_replace($path, '/templates', strrpos($path, '/'), 0);
		$path = substr_replace($path, '/templates', strrpos($path, '/'));
		return array(
			"$appDir$pathP/$view.phtml",
			"$appDir$pathP.$view.phtml",
			"$appDir$path/@global.$view.phtml",
			NEURON_DIR . "$pathP/$view.phtml",
			NEURON_DIR . "$pathP.$view.phtml",
			NEURON_DIR . "$path/@global.$view.phtml",
		);
	}


	
	public function formatLayoutTemplateFiles($presenter, $layout)
	{
		$appDir = Environment::getVariable('appDir');
		$path = '/' . str_replace(':', 'Module/', $presenter);
		$pathP = substr_replace($path, '/templates', strrpos($path, '/'), 0);
		$list = array(
			"$appDir$pathP/@$layout.phtml",
			"$appDir$pathP.@$layout.phtml",
			NEURON_DIR . "$pathP/@$layout.phtml",
			NEURON_DIR . "$pathP.@$layout.phtml",
		);
		while (($path = substr($path, 0, strrpos($path, '/'))) !== FALSE) {
			$list[] = "$appDir$path/templates/@$layout.phtml";
			$list[] = NEURON_DIR . "$path/templates/@$layout.phtml";
		}
		return $list;
	}

}
