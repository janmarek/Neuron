<?php

namespace Neuron;

use Nette\Environment, Nette\String;
use Texy, TexyHeadingModule;
use Nette\Templates\Template, Nette\Templates\LatteFilter;

/**
 * My Texy
 *
 * @author Honza
 */
class Texy extends Texy {

	public static $advertisingNotice = false;

	public function __construct() {
		parent::__construct();

		// output
		$this->setOutputMode(self::HTML4_TRANSITIONAL);
		$this->htmlOutputModule->removeOptional = FALSE;
		$this->htmlOutputModule->lineWrap = false;

		$this->headingModule->balancing = TexyHeadingModule::FIXED;
		$this->allowed['phrase/sup'] = true;
		$this->allowed['phrase/sub'] = true;
		$this->allowed['phrase/del'] = true;

		// images
		$this->imageModule->fileRoot = WWW_DIR . "/data/files";
		$this->imageModule->root = Environment::getVariable("baseUri") . "data/files";

		// align classes
		$this->alignClasses['left'] = 'left';
		$this->alignClasses['right'] = 'right';
		$this->alignClasses['center'] = 'center';
		// justify, top, bottom, middle

		$this->imageModule->leftClass = "image-left";
		$this->imageModule->rightClass = "image-right";

		$this->figureModule->class = "figure";
		$this->figureModule->leftClass = "figure-left";
		$this->figureModule->rightClass = "figure-right";
		$this->figureModule->widthDelta = 0;


		// handlers
		$this->addHandler('afterTable', array($this, 'afterTable'));
		$this->addHandler('image', array($this, 'youtubeHandler'));
		$this->addHandler('image', array($this, 'flashHandler'));
		$this->addHandler("phrase", array($this, "netteLink"));
	}
	


	/**
	 * @param TexyHandlerInvocation  handler invocation
	 * @param string
	 * @param string
	 * @param TexyModifier
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function netteLink($invocation, $phrase, $content, $modifier, $link)
	{
		// is there link?
		if (!$link) return $invocation->proceed();

		$url = $link->URL;

		if (String::startsWith($url, "plink://")) {
			$url = substr($url, 8);
			list($presenter, $params) = explode("?", $url, 2);

			$arr = array();

			if ($params) {
				parse_str($params, $arr);
			}

			$link->URL = Environment::getApplication()->getPresenter()->link($presenter, $arr);
		}

		return $invocation->proceed();
	}



	/**
	 * Template factory
	 * @return Template
	 */
	private function createTemplate()
	{
		$template = new Template;
		$template->registerFilter(new LatteFilter);
		return $template;
	}



	/**
	 * YouTube handler for images
	 *
	 * @example [* youtube:JG7I5IF6 *]
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function youtubeHandler($invocation, $image, $link)
	{
		$parts = explode(':', $image->URL, 2);

		if (count($parts) !== 2 || $parts[0] !== "youtube") {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(NEURON_DIR . "/templates/youtube.phtml");
		$template->id = $parts[1];
		if ($image->width) $template->width = $image->width;
		if ($image->height) $template->height = $image->height;

		return $this->protect((string) $template, Texy::CONTENT_BLOCK);
	}



	/**
	 * Flash handler for images
	 *
	 * @example [* flash.swf 200x150 .(alternative content) *]
	 *
	 * @param TexyHandlerInvocation  handler invocation
	 * @param TexyImage
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public function flashHandler($invocation, $image, $link)
	{
		if (!String::endsWith($image->URL, ".swf")) {
			return $invocation->proceed();
		}

		$template = $this->createTemplate()->setFile(NEURON_DIR . "/templates/flash.phtml");
		$template->url = Texy::prependRoot($image->URL, $this->imageModule->root);
		$template->width = $image->width;
		$template->height = $image->height;
		if ($image->modifier->title) $template->title = $image->modifier->title;

		return $this->protect((string) $template, Texy::CONTENT_BLOCK);
	}



	/**
	 * @param  TexyBlockParser
	 * @param  TexyHtml
	 * @param  TexyModifier
	 * @return void
	 */
	function afterTable($parser, $element, $modifier) {
		$element->attrs['class'] = 'table';
	}
}