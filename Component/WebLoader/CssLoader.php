<?php

namespace Neuron\Webloader;

use Nette\Environment;
use cssmin;
use WebLoader;

/**
 * CssLoader
 *
 * @author Jan Marek
 */
class CssLoader extends WebLoader\CssLoader
{
	public function __construct(\Nette\IComponentContainer $parent = null, $name = null)
	{
		parent::__construct($parent, $name);

		$this->setSourcePath(WWW_DIR . "/css");
		$this->setTempUri(Environment::getVariable("baseUri") . "data/webtemp");
		$this->setTempPath(WWW_DIR . "/data/webtemp");
		$this->setJoinFiles(Environment::isProduction());

		$this->fileFilters[] = new Webloader\LessFilter;

		if (Environment::isProduction()) {
			$this->filters[] = function ($code) {
				return cssmin::minify($code, "remove-last-semicolon");
			};
		}

		$this->init();
	}



	protected function init()
	{

	}

}