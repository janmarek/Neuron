<?php

namespace Neuron;

use Gridito\Grid;
use Nette\Web\Html;
use Nette\Web\HttpUploadedFile;
use Neuron\Image\ThumbnailHelper;

/**
 * Photogallery admin
 */
class PhotogalleryAdmin extends BaseControl
{
	/** @var \Neuron\Model\Photo\GalleryService */
	private $service;

	/** @var \Neuron\Model\Photo\Gallery */
	private $gallery;



	public function setPhotogalleryService($service)
	{
		$this->service = $service;
	}



	public function getPhotogalleryService()
	{
		return $this->service;
	}



	public function setGallery($gallery)
	{
		$this->gallery = $gallery;
	}



	public function getGallery()
	{
		return $this->gallery;
	}



	/**
	 * Render control
	 */
	public function render()
	{
		$this->template->render();
	}



	protected function createComponentUpload($name)
	{
		$upload = new UploadControl($this, $name);

		$gallery = $this->getGallery();
		$service = $this->getPhotogalleryService()->getPhotoService();

		$upload->setHandler(function (HttpUploadedFile $file) use ($gallery, $service) {
			try {
				$service->createAndUploadPhoto($gallery, array(), $file);
			} catch (\Neuron\Model\ValidationException $e) {
				// todo
			}
		});

		$upload->setRedirectUri($this->link("this"));
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);

		$photoService = $this->getPhotogalleryService()->getPhotoService();
		$model = $photoService->getFinder()->whereGallery($this->getGallery())->getGriditoModel();

		$grid->setModel($model);

		// columns

		$grid->addColumn("image", "Náhled")->setRenderer(function ($photo) {
			$thumb = ThumbnailHelper::createThumbnail($photo->getImage(), 80, 80);
			echo Html::el("a")->href($photo->image->src)->target("_blank")->add($thumb->getHtml());
		});
		$grid->addColumn("description", "Popis")->setSortable(true);

		// buttons

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("delete", "Smazat", array(
			"handler" => function ($entity) use ($service, $presenter, $grid) {
				$service->delete($entity);
				$galleryId = $entity->getGallery()->getId();
				$presenter->flashMessage("Fotografie byl úspěšně smazána.");
				$grid->redirect("this");
			},
			"icon" => "ui-icon-closethick",
			"confirmationQuestion" => "Opravdu chcete smazat fotografii?"
		));
	}

}