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
			"base.css",

			"blueprint/reset.css",
			"blueprint/ie.css",
			"blueprint/grid.css",
			"blueprint/typography.css",
			"blueprint/forms.css",

			"gridito.css",
			WWW_DIR . "/js/texyla/css/style.css",
		));
	}
}