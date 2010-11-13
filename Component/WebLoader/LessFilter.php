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
		
	private function getLessC()
	{
		if (empty($this->lc)) {
			$this->lc = new lessc;
		}

		return $this->lc;
	}
	
	/**
	 * Invoke filter
	 * @param string code
	 * @param WebLoader loader
	 * @param string file
	 * @return string
	 */
	public function __invoke($code, WebLoader $loader, $file)
	{
		if (String::endsWith($file, ".less")) {
			return $this->getLessC()->parse($code);
		}
		
		return $code;
	}
}
