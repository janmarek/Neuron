<?php

namespace Neuron\Model\Photo;

/**
 * Photo service
 *
 * @author Jan Marek
 */
class PhotoService extends \Neuron\Model\Service
{
	protected $maxWidth = 800;

	protected $maxHeight = 600;

	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Photo");
	}



	public function createAndUploadPhoto(Gallery $gallery, array $values, \Nette\Web\HttpUploadedFile $file)
	{
		if (!$file->isImage()) {
			throw new ValidationException("Soubor není obrázek.");
		}

		$photo = $this->createBlank();
		$this->update($photo, $values);
		$gallery->addPhoto($photo);
		@mkdir(pathinfo($photo->getFilePath(), PATHINFO_DIRNAME), 0777, true);
		$file->toImage()->resize($this->maxWidth, $this->maxHeight)->save($photo->getFilePath());
		$this->save($gallery);
	}



	public function getFinder()
	{
		return new PhotoFinder($this);
	}

}