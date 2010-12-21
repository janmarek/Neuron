<?php

namespace Neuron\Texy;

use Nette\Environment;

/**
 * Texy helper
 *
 * @author Jan Marek
 */
class TemplateHelper extends \Nette\Object
{
	/**
	 * @param string texy source
	 * @return string html code
	 */
	public static function process($text)
	{
		return Environment::getService("Texy")->process($text);
	}

	/**
	 * @param string texy source
	 * @return string html code
	 */
	public static function safeProcess($text)
	{
		return Environment::getService("SafeTexy")->process($text);
	}

}
