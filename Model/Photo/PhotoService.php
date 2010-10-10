<?php

namespace Neuron\Model;

/**
 * Photo service
 *
 * @author Jan Marek
 */
class PhotoService extends Service
{
	protected $maxWidth = 800;

	protected $maxHeight = 600;

	public function __construct($em)
	{
		parent::__construct($em, "Neuron\Model\Photogallery");
	}



	public function createBlankPhoto()
	{
		return new Photo;
	}



	public function createAndUploadPhoto(Photogallery $gallery, array $values, \Nette\Web\HttpUploadedFile $file)
	{
		if (!$file->isImage()) {
			throw new ValidationException("Soubor není obrázek.");
		}
		
		$photo = $this->createBlankPhoto();
		$this->update($photo, $values);
		$gallery->addPhoto($photo);
		@mkdir(pathinfo($photo->getFilePath(), PATHINFO_DIRNAME), 0777, true);
		$file->toImage()->resize($this->maxWidth, $this->maxHeight)->save($photo->getFilePath());
		$this->save($gallery);
	}

	

	public function getPhotosGriditoModel($id)
	{
		$id = (int) $id;
		$model = new \Gridito\DoctrineModel($this->getEntityManager(), "Neuron\Model\Photo");
		$model->getQuery()
			->leftJoin("e.gallery", "g")
			->where("g.id = $id");
		return $model;
	}
	
}