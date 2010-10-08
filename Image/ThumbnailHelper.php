<?php

namespace Neuron\Image;

use Nette\Image;

/**
 * Thumbnail helper
 *
 * @author Jan Marek
 */
class ThumbnailHelper
{
	public static $thumbnailDir;

	public static function helper(HtmlImage $image, $width, $height, $flags = Image::FIT)
	{
		$root = self::$thumbnailDir ?: TEMP_DIR . "/webtemp";

		$path = $image->getFilePath();
		$fileName = "thumb-" . md5($path . filemtime($path) . $flags) . "-" . $width . "x" . $height . ".jpg";
		$thumbPath = $root . "/" . $fileName;

		if (!file_exists($thumbPath)) {
			Image::fromFile($path)->resize($width, $height, $flags)->save($thumbPath);
		}

		$thumb = new HtmlImage($thumbPath);
		$thumb->setAlt($image->getAlt());

		return $thumb;
	}
	
}