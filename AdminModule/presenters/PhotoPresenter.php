<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Nette\Web\HttpUploadedFile;
use Neuron\UploadControl;

/**
 * Photo presenter
 *
 * @author Jan Marek
 */
class PhotoPresenter extends \Neuron\Presenter\AdminModule\AdminPresenter
{
	/** @var Neuron\Model\Photo\Service */
	private $service;

	/** @var Neuron\Model\Photo\GalleryService */
	private $galleryService;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("PhotoService");
		$this->galleryService = $this->getService("PhotogalleryService");
	}



	public function renderDefault($id)
	{
		$this->template->title = "Fotogalerie " . $this->galleryService->find($id)->name;
	}



	public function renderEdit($id)
	{
		$this->template->title = "Upravit fotogalerii";
	}



	public function renderEditPhoto($id)
	{
		$this->template->title = "Upravit fotografii";
	}



	public function renderSortPhotos($id)
	{
		$this->template->title = "Organizovat fotografie";
		$this->template->photos = $this->galleryService->find($id)->getPhotos();
	}



	public function handleSaveSort($id)
	{
		// load photos
		$this->galleryService->find($id)->getPhotos()->toArray();

		foreach ($this->getParam("photo") as $order => $photoId) {
			$this->service->find($photoId)->setItemOrder($order + 1);
		}

		$this->service->getEntityManager()->flush();
		$this->flashMessage('Fotografie byly přesunuty.');
		$this->redirect('default', $id);
	}



	protected function createComponentUpload($name)
	{
		$upload = new UploadControl($this, $name);

		$gallery = $this->galleryService->find($this->getParam("id"));
		$service = $this->service;

		$upload->setHandler(function (HttpUploadedFile $file) use ($gallery, $service) {
			try {
				$service->uploadPhoto($gallery, array(), $file);
			} catch (\Neuron\Model\ValidationException $e) {
				// todo
			}
		});

		$upload->setRedirectUri($this->link("this"));
	}



	protected function createComponentEditGalleryForm($name)
	{
		$form = new \Neuron\Form\GalleryForm($this, $name);
		$form->bindEntity($this->galleryService->find($this->getParam('id')));
		$form->setEntityService($this->galleryService);
		$form->setSuccessFlashMessage('Galerie byla upravena.');
		$form->setRedirect('default', $this->getParam('id'));
	}



	protected function createComponentEditPhotoForm($name)
	{
		$form = new \Neuron\Form\PhotoForm($this, $name);
		$photo = $this->service->find($this->getParam('id'));
		$form->bindEntity($photo);
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage('Fotografie byla upravena.');
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);

		$gallery = $this->galleryService->find($this->getParam('id'));
		$model = $this->service->getFinder()->whereGallery($gallery)->orderByOrder()->getGriditoModel();

		$grid->setModel($model);

		// columns

		$template = $this->createTemplate()->setFile(__DIR__ . "/../templates/Photo/@thumb.phtml");
		$grid->addColumn("image", "Náhled")->setRenderer(function ($photo) use ($template) {
			$template->image = $photo;
			$template->render();
		});
		$grid->addColumn("description", "Popis")->setSortable(true);
		$grid->addColumn('itemOrder', 'Pořadí')->setSortable(true);

		// buttons

		$presenter = $this;

		$grid->addToolbarButton("edit", "Upravit galerii", array(
			"icon" => "ui-icon-pencil",
			"link" => $this->link("edit", $this->getParam("id")),
		));

		$grid->addToolbarButton("sort", "Řadit fotografie", array(
			"icon" => "ui-icon-arrow-4",
			"link" => $this->link("sortPhotos", $this->getParam("id")),
		));

		$photoService = $this->service;

		$grid->addButton("editPhoto", "Upravit", array(
			"icon" => "ui-icon-pencil",
			"link" => function ($entity) use ($presenter) {
				return $presenter->link("editPhoto", $entity->getId());
			},
		));

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
