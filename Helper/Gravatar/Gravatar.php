<?php

namespace Neuron\Helper;

use Nette\Web\Html;

/**
 * Gravatar helper
 *
 * @author Jan Marek
 */
class Gravatar
{
	public static function getImageTag($mail, $size = 32)
	{
		$hash = md5(\Nette\String::lower($mail));
		$src = "http://www.gravatar.com/avatar/$hash?d=mm&s=$size";
		return Html::el('img')->src($src)->alt('')->width($size)->height($size);
	}

}
