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
	/** @var \Neuron\Model\PhotoService */
	private $service;



	public function init()
	{
		$this->service = $this->getService("PhotoService");
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

		$gallery = $this->service->find($this->getParam("id"));
		$service = $this->service;

		$upload->setHandler(function (HttpUploadedFile $file) use ($gallery, $service) {
			try {
				$service->createAndUploadPhoto($gallery, array(), $file);
			} catch (\Neuron\Model\ValidationException $e) {
				// todo
			}
		});

		$upload->setRedirectUri($this->link("this"));
	}



	protected function createComponentPhotosGrid($this, $name)
	{
		$grid = new Grid($this, $name);

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
			"icon" => "closethick",
			"confirmationQuestion" => "Opravdu chcete smazat fotografii?"
		));
	}

}