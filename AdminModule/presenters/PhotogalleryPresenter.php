<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Neuron\Form\PhotogalleryForm;
use Neuron\UploadControl;

/**
 * Photogallery presenter
 *
 * @author Jan Marek
 */
class PhotogalleryPresenter extends AdminPresenter
{
	/** @var \Neuron\Model\PhotoService */
	private $service;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("PhotoService");
	}



	public function actionDefault()
	{
		$this->template->title = "Fotogalerie";
	}



	public function actionAdd()
	{
		$this->template->title = "Přidat fotogalerii";
	}



	public function actionGalleryDetail($id)
	{
		$gallery = $this->service->find($id);
		$this->template->title = trim("Upravit fotogalerii $gallery->description");
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



	protected function createComponentGrid()
	{
		$grid = new Grid;

		$grid->setModel($this->service->getGriditoModel());

		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("description", "Popis")->setSortable(true);

		$presenter = $this;
		$service = $this->service;

		$grid->addToolbarWindowButton("Nová fotogalerie", function () use ($presenter) {
			$presenter["addGalleryForm"]->render();
		}, "plusthick");

		$grid->addButton("Detaily", null)
			->setLink(function ($entity) use ($presenter) {
				return $presenter->link("galleryDetail", $entity->id);
			});

		$grid->addButton("Smazat", function ($entity) use ($service, $presenter, $grid) {
			$service->delete($entity);
			$presenter->flashMessage("Fotogalerie byl úspěšně smazána.");
			$presenter->redirect("default");
		}, "closethick")
			->setConfirmationQuestion("Opravdu chcete smazat fotogalerii?");

		return $grid;
	}



	protected function createComponentPhotosGrid()
	{
		$grid = new Grid;

		$grid->setModel($this->service->getPhotosGriditoModel($this->getParam("id")));

		$grid->addColumn("image", "Náhled", function ($photo) {
			$thumb = \Neuron\Image\ThumbnailHelper::helper($photo->getImage(), 80, 80);
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



	protected function createComponentAddGalleryForm($name)
	{
		$form = new PhotogalleryForm($this, $name);
		$form->bindEntity($this->service->createBlank());
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage("Nová fotogalerie byla úspěšně vytvořena.");
	}
}