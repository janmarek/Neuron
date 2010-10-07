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
			"base.css",

			"blueprint/reset.css",
			"blueprint/ie.css",
			"blueprint/grid.css",
			"blueprint/typography.css",
			"blueprint/forms.css",
		));
	}
}