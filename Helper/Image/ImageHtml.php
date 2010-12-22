<?php

namespace Neuron\Helper;

use Nette\Environment;
use Nette\Web\Html;

/**
 * Html image
 *
 * @author Jan Marek
 */
class ImageHtml
{
	public static function getHtml($path, $alt = "")
	{
		$path = realpath($path);

		if (!$path) {
			throw new \FileNotFoundException("File '$path' does not exist.");
		}

		$imageSize = @getimagesize($path);

		if (!$imageSize) {
			throw new \InvalidStateException("File '$path' is not image.");
		}

		return Html::el("img", array(
			"src" => WebPath::getSrc($path),
			"width" => $imageSize[0],
			"height" => $imageSize[1],
			"alt" => $alt,
		));
	}

}