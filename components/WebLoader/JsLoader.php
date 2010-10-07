<?php

namespace Neuron\Webloader;

use Nette\Environment;
use WebLoader\VariablesFilter;

/**
 * AdminJsLoader
 *
 * @author Jan Marek
 */
class JsLoader extends \WebLoader\JavaScriptLoader
{
	public function __construct(\Nette\IComponentContainer $parent = null, $name = null)
	{
		parent::__construct($parent, $name);
		
		$this->setJoinFiles(Environment::isProduction());
		$this->setTempUri(Environment::getVariable("baseUri") . "data/webtemp");
		$this->setTempPath(WWW_DIR . "/data/webtemp");
		$this->setSourcePath(WWW_DIR . "/js");

		$presenter = Environment::getApplication()->getPresenter();

		$this->filters[] = new VariablesFilter(array(
			// texyla
			"baseUri" => Environment::getVariable("baseUri"),
			"texylaPreviewPath" => $presenter->link(":Texyla:preview"),
			"texylaFilesPath" => $presenter->link(":Texyla:listFiles"),
			"texylaFilesUploadPath" => $presenter->link(":Texyla:upload"),
		));

		if (Environment::isProduction()) {
			$this->filters[] = "JSMin::minify";
		}

		$this->init();
	}

	

	protected function init()
	{

	}
}