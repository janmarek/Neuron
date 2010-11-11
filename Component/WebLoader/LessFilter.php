<?php

namespace Webloader;

use Nette\String, lessc;

/**
 * Less CSS filter
 *
 * @author Jan Marek
 * @license MIT
 */
class LessFilter
{
	private $lc;
		
	public function __construct()
	{
		$this->lc = new lessc;
	}
	
	/**
	 * Invoke filter
	 * @param string code
	 * @param WebLoader loader
	 * @param string file
	 * @return string
	 */
	public function __invoke($code, WebLoader $loader, $file = null)
	{
		if (String::endsWith($file, ".less")) {
			return $this->lc->parse($code);
		}
		
		return $code;
	}
}
