<?php

namespace Neuron\Presenter\AdminModule;

abstract class AdminPresenter extends \Neuron\Application\BasePresenter
{
	public function startup()
	{
		parent::startup();

		// user authentication
		if (!$this->getUser()->isInRole("admin")) {
			if ($this->getUser()->getLogoutReason() === \Nette\Web\User::INACTIVITY) {
				$this->flashMessage('You have been logged out due to inactivity. Please login again.');
			}

			throw new \Nette\Application\ForbiddenRequestException;
		}
	}



	protected function createComponentJs()
	{
		$js = parent::createComponentJs();
		$js->clear();
		$js->addFiles(array(
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

			"jquery.livequery.js",
			"nette.js",
			"web.js",
			"logged.js",
			"netteForms.js",
			LIBS_DIR . "/Gridito/js/grid.js"
		));

		return $js;
	}
}