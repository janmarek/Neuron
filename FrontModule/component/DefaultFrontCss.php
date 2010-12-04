<?php

namespace Neuron\Webloader;

/**
 * Default front CSS
 *
 * @author Jan Marek
 */
class DefaultFrontCss extends CssLoader
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
		));
	}
}