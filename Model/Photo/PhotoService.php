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

	protected $repository;



	public function __construct($em, $repository)
	{
		parent::__construct($em, __NAMESPACE__ . "\Photo");
		$this->repository = $repository;
	}



	public function uploadPhoto(Gallery $gallery, array $values, \Nette\Web\HttpUploadedFile $file)
	{
		if (!$file->isImage()) {
			throw new \Neuron\Model\ValidationException("File is not image.");
		}

		// save image
		$image = $file->toImage()->resize($this->maxWidth, $this->maxHeight);
		$values['hash'] = $this->repository->save((string) $image);

		// save entity
		$em = $this->getEntityManager();
		$photo = new Photo($values);
		$em->persist($photo);
		$gallery->addPhoto($photo);
		$em->flush();
	}



	public function getImagePath($image)
	{
		return $this->repository->getPath($image->getHash());
	}



	public function getFinder()
	{
		return new PhotoFinder($this);
	}

}