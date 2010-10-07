<?php

namespace Neuron\Webloader;

/**
 * Default front js
 *
 * @author Jan Marek
 */
class DefaultFrontJs extends JsLoader
{
	protected function init()
	{
		$this->addFiles(array(
			"jquery.livequery.js",
			"nette.js",
			"web.js",
			"netteForms.js",
		));
	}
}