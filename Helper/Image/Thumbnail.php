<?php

namespace Neuron\Helper;

use Nette\Image;

/**
 * Thumbnail helper
 *
 * @author Jan Marek
 */
class Thumbnail
{
	/** @var Neuron\IO\FileRepository */
	private $repository;



	public function __construct($repository)
	{
		$this->repository = $repository;
	}



	public function createThumbnail($path, $width, $height, $type = 'normal')
	{
		$key = md5($path . "|" . filemtime($path) . "|" . $width . "|" . $height . "|" . $type) . ".jpg";

		if (!$this->repository->exist($key)) {
			$image = Image::fromFile($path);
			if ($type == 'normal') {
				$image = $image->resize($width, $height);
			} else {
				$image = $image->resize($width, $height, Image::FILL)->crop("50%", "25%", $width, $height);
			}
			$this->repository->save((string) $image, $key);
		}

		return $this->repository->getPath($key);
	}

}