<?php

namespace Neuron\Webloader;

/**
 * Default admin js
 *
 * @author Jan Marek
 */
class DefaultAdminCss extends CssLoader
{
	protected function init()
	{
		$this->addFiles(array(
			NEURON_DIR . "/css/ajax.css",

			NEURON_DIR . "/css/blueprint/reset.css",
			NEURON_DIR . "/css/blueprint/ie.css",
			NEURON_DIR . "/css/blueprint/grid.css",
			NEURON_DIR . "/css/blueprint/typography.css",
			NEURON_DIR . "/css/blueprint/forms.css",

			NEURON_DIR . "/vendor/Gridito/css/gridito.css",
			WWW_DIR . "/js/texyla/css/style.css",
		));
	}
}