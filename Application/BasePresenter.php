<?php

namespace Neuron\Application;

use Nette\Environment;
use Navigation;
use WebLoader;

/**
 * @property-read \Nette\Context $context
 */
abstract class BasePresenter extends \Nette\Application\Presenter
{
	protected function startup()
	{
		parent::startup();
		Neuron\Form\FormMacros::register();
	}



	/**
	 * Get context
	 * @return \Nette\Context
	 */
	public function getContext()
	{
		return $this->getApplication()->getContext();
	}



	public function getService($name)
	{
		return $this->getContext()->getService($name);
	}



	/**
	 * @return Nette\Templates\Template
	 */
	public function createTemplate()
	{
		$template = parent::createTemplate();
		$template->registerHelper("texy", "Neuron\Texy\TexyHelper::process");
		return $template;
	}

	

	public function handleLogOut()
	{
		$this->getUser()->logout();
		$this->flashMessage("Byl jste úspěšně odhlášen.");
		$this->redirect(":Homepage:");
	}



	public function formatTemplateFiles($presenter, $view)
	{
		parent::formatTemplateFiles($presenter, $view);
	}


	
	public function formatLayoutTemplateFiles($presenter, $layout)
	{
		parent::formatLayoutTemplateFiles($presenter, $layout);
	}

	// <editor-fold defaultstate="collapsed" desc="components">

	// <editor-fold defaultstate="collapsed" desc="login form">

	/**
	 * Login form component factory
	 */
	protected function createComponentLoginForm()
	{
		return new LoginForm;
	}

	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="navigation">

	protected function createComponentMenu($name) {
		$nav = new Navigation($this, $name);

		$nav->setupHomepage("Úvod", $this->link("Homepage:"))
			->setAsCurrent($this->name === "Homepage");
	}
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="webloader">

	protected function createComponentJs() {
		$js = new WebLoader\JavaScriptLoader;

		$js->tempUri = Environment::getVariable("baseUri") . "data/webtemp";
		$js->tempPath = WWW_DIR . "/data/webtemp";
		$js->sourcePath = WWW_DIR . "/js";

		$js->filters[] = new WebLoader\VariablesFilter(array(
			// texyla
			"baseUri" => Environment::getVariable("baseUri"),
			"texylaPreviewPath" => $this->link(":Texyla:preview"),
			"texylaFilesPath" => $this->link(":Texyla:listFiles"),
			"texylaFilesUploadPath" => $this->link(":Texyla:upload"),
		));

		$js->filters[] = "JSMin::minify";

		return $js;
	}

	protected function createComponentCss() {
		$css = new WebLoader\CssLoader;
		$css->sourcePath = WWW_DIR . "/css";
		$css->tempUri = Environment::getVariable("baseUri") . "data/webtemp";
		$css->tempPath = WWW_DIR . "/data/webtemp";

		$css->filters[] = function ($code) {
			return cssmin::minify($code, "remove-last-semicolon");
		};

		return $css;
	}

	// </editor-fold>

	// </editor-fold>

}
