<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Neuron\Form\TagForm;

/**
 * Tag presenter
 *
 * @author Jan Marek
 */
class TagPresenter extends AdminPresenter
{
	/** @var Neuron\Model\Tag\Service */
	private $service;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("TagService");
	}



	public function renderDefault()
	{
		$this->template->title = "Tagy";
	}



	public function actionAdd()
	{
		$this->template->title = "Přidat tag";
		$this["form"]->bindEntity($this->service->createBlank());
		$this["form"]->setSuccessFlashMessage("Tag byl přidán.");
	}



	public function actionEdit($id)
	{
		$entity = $this->service->find($id);
		$this->template->title = "Upravit $entity->name";
		$this["form"]->bindEntity($entity);
		$this["form"]->setSuccessFlashMessage("Tag byl upraven.");
	}



	protected function createComponentForm($name)
	{
		$form = new TagForm($this, $name);
		$form->setEntityService($this->service);
		$form->setRedirect("default");
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->service->getFinder()->getGriditoModel());

		$grid->addColumn("name", "Název")->setSortable(true);
		$grid->addColumn("itemCount", "Počet článků")->setSortable(true);

		$presenter = $this;

		$grid->addButton("edit", "Upravit", array(
			"icon" => "ui-icon-pencil",
			"link" => function ($entity) use ($presenter) {
				return $presenter->link("edit", $entity->getId());
			},
		));

		$service = $this->service;

		$grid->addButton("delete", "Smazat", array(
			"icon" => "ui-icon-closethick",
			"handler" => function ($entity) use ($presenter, $service) {
				$service->delete($entity);
				$presenter->flashMessage("Tag byl smazán.");
				$presenter->redirect("this");
			},
		));

		$grid->addToolbarButton("add", "Přidat", array(
			"icon" => "ui-icon-plusthick",
			"link" => $this->link("add"),
		));
	}
}