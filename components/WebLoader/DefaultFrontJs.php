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
			NEURON_DIR . "/js/jquery.livequery.js",
			NEURON_DIR . "/js/nette.ajax.js",
			NEURON_DIR . "/js/neuron.ajaxsetup.js",
			NEURON_DIR . "/js/nette.forms.js",
		));
	}
}