<?php

namespace Neuron\Helper;

/**
 * WebPath
 *
 * @author Jan Marek
 */
class WebPath
{
	public static function getSrc($path)
	{
		$src = rtrim(\Nette\Environment::getVariable('baseUri'), '/') . substr($path, strlen(WWW_DIR));
		$src = strtr($src, "\\", "/");
		return $src;
	}

}
