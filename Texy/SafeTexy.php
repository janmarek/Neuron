<?php

namespace Neuron\Texy;

class SafeTexy extends Texy
{
	public function __construct()
	{
		parent::__construct();

		// safe mode
		\TexyConfigurator::safeMode($this);

		// output
		$this->setOutputMode(self::HTML4_TRANSITIONAL);
		$this->htmlOutputModule->removeOptional = false;
		self::$advertisingNotice = false;

		$this->allowed['heading/surrounded'] = false;
		$this->allowed['heading/underlined'] = false;
		$this->allowed['link/definition'] = false;
		$this->allowed['image/definition'] = false;

		$this->mergeLines = false;

		$this->addHandler('phrase', array(__CLASS__, 'linkHandler'));
	}



	/**
	 * @param TexyHandlerInvocation  handler invocation
	 * @param string
	 * @param string
	 * @param TexyModifier
	 * @param TexyLink
	 * @return TexyHtml|string|FALSE
	 */
	public static function linkHandler($invocation, $phrase, $content, $modifier, $link)
	{
		$el = $invocation->proceed();

		if ($el instanceof \TexyHtml && $el->getName() === 'a') {
			$el->attrs['target'] = '_blank';
			$el->attrs['rel'] = 'nofollow';
		}

		return $el;
	}

}