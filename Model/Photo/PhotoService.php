<?php

namespace Neuron\Model;

/**
 * PhotoService
 *
 * @author Jan Marek
 */
class PhotoService extends Service
{
	protected $maxWidth = 800;

	protected $maxHeight = 600;

	public function __construct($em)
	{
		parent::__construct($em, "Neuron\Model\Photo");
	}



	public function createAndUploadPhoto(Photogallery $gallery, array $values, \Nette\Web\HttpUploadedFile $file)
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

	
	
	protected function createQueryBuilder()
	{
		$repository = $this->getEntityManager()->getRepository($entityName);
		return $repository->createQueryBuilder("e")->leftJoin("e.gallery", "g");
	}
	
	
	
	/**
	 * @return PhotoFinder
	 */
	public function getFinder()
	{
		return new PhotoFinder($this->createQueryBuilder());
	}

}