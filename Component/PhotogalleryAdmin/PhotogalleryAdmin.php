<?php

namespace Neuron;

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

		$upload->setHandler(function (\Nette\Web\HttpUploadedFile $file) use ($gallery, $service) {
			try {
				$service->createAndUploadPhoto($gallery, array(), $file);
			} catch (\Neuron\Model\ValidationException $e) {
				// todo
			}
		});

		$upload->setRedirectUri($this->link("this"));
	}



	protected function createComponentPhotosGrid()
	{
		$grid = new Grid;

		$grid->setModel($this->service->getPhotosGriditoModel($this->getParam("id")));

		$grid->addColumn("image", "Náhled", function ($photo) {
			$thumb = \Neuron\Image\ThumbnailHelper::createThumbnail($photo->getImage(), 80, 80);
			echo \Nette\Web\Html::el("a")->href($photo->image->src)->target("_blank")->add($thumb->getHtml());
		});
		$grid->addColumn("description", "Popis")->setSortable(true);

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("Smazat", function ($entity) use ($service, $presenter, $grid) {
			$service->delete($entity);
			$galleryId = $entity->getGallery()->getId();
			$presenter->flashMessage("Fotografie byl úspěšně smazána.");
			$presenter->redirect("galleryDetail", $galleryId);
		}, "closethick")
			->setConfirmationQuestion("Opravdu chcete smazat fotografii?");

		return $grid;
	}

}