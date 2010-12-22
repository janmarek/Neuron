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
	/** @var \Neuron\Model\Photo\Gallery */
	private $gallery;



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
		$service = $this->getService('PhotoService');

		$upload->setHandler(function (HttpUploadedFile $file) use ($gallery, $service) {
			try {
				$service->uploadPhoto($gallery, array(), $file);
			} catch (\Neuron\Model\ValidationException $e) {
				// todo
			}
		});

		//$upload->setRedirectUri($this->link("this"));
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);

		$photoService = $this->getService('PhotoService');
		$model = $photoService->getFinder()->whereGallery($this->getGallery())->getGriditoModel();

		$grid->setModel($model);

		// columns

		$template = $this->createTemplate()->setFile(__DIR__ . "/thumb.phtml");

		$grid->addColumn("image", "Náhled")->setRenderer(function ($photo) use ($template) {
			$template->image = $photo;
			//echo $template->imagehtml($template->thumbnail($template->imagepath($photo), 80, 80));
			$template->render();
		});
		$grid->addColumn("description", "Popis")->setSortable(true);

		// buttons

		$presenter = $this;

		$grid->addButton("delete", "Smazat", array(
			"handler" => function ($entity) use ($photoService, $presenter, $grid) {
				$photoService->delete($entity);
				$galleryId = $entity->getGallery()->getId();
				$presenter->flashMessage("Fotografie byl úspěšně smazána.");
				$grid->redirect("this");
			},
			"icon" => "ui-icon-closethick",
			"confirmationQuestion" => "Opravdu chcete smazat fotografii?"
		));
	}

}