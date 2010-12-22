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



	public function createThumbnail($path, $width, $height, $flags = Image::FIT)
	{
		$key = md5($path . "|" . filemtime($path) . "|" . $width . "|" . $height . "|" . $flags) . ".jpg";

		if (!$this->repository->exist($key)) {
			$image = Image::fromFile($path)->resize($width, $height, $flags);
			$this->repository->save((string) $image, $key);
		}

		return $this->repository->getPath($key);
	}

}