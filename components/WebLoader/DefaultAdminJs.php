<?php

namespace Neuron\Webloader;

/**
 * Default admin js
 *
 * @author Jan Marek
 */
class DefaultAdminJs extends JsLoader
{
	protected function init()
	{
		$this->addFiles(array(
			// texyla

			// core
			"texyla/js/texyla.js",
			"texyla/js/selection.js",
			"texyla/js/texy.js",
			"texyla/js/buttons.js",
			"texyla/js/dom.js",
			"texyla/js/view.js",
			"texyla/js/ajaxupload.js",

			// languages
			"texyla/languages/cs.js",
			//"texyla/languages/sk.js",
			//"texyla/languages/en.js",

			// plugins
			"texyla/plugins/keys/keys.js",
			"texyla/plugins/window/window.js",
			"texyla/plugins/resizableTextarea/resizableTextarea.js",
			"texyla/plugins/img/img.js",
			"texyla/plugins/table/table.js",
			"texyla/plugins/link/link.js",
			//"texyla/plugins/emoticon/emoticon.js",
			"texyla/plugins/symbol/symbol.js",
			"texyla/plugins/files/files.js",
			//"texyla/plugins/color/color.js",
			"texyla/plugins/textTransform/textTransform.js",
			"texyla/plugins/youtube/youtube.js",

			// other

			NEURON_DIR . "/js/jquery.livequery.js",
			NEURON_DIR . "/js/jquery.html5upload.js",
			NEURON_DIR . "/js/nette.ajax.js",
			NEURON_DIR . "/js/neuron.ajaxsetup.js",
			NEURON_DIR . "/js/neuron.logged.js",
			NEURON_DIR . "/js/nette.forms.js",
			NEURON_DIR . "/vendor/Gridito/js/gridito.js",
		));
	}
}