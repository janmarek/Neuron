<?php

namespace Neuron\Presenter\AdminModule;

use Gridito;
use Neuron\Form\PageForm;

class PagesPresenter extends AdminPresenter
{
	/** @var \Neuron\Model\Service */
	private $service;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("PageService");
	}



	public function actionDefault()
	{
		$this->template->title = "Stránky";
	}



	public function actionAdd()
	{
		$this->template->title = "Vytvořit stránku";
		$this["form"]->bindEntity($this->service->createBlank());
		$this["form"]->setSuccessFlashMessage("Stránka byla úspěšně založena.");
	}



	public function actionEdit($id)
	{
		$entity = $this->service->find($id);
		$this->template->title = "Upravit stránku $entity->name";
		$this["form"]->bindEntity($entity);
		$this["form"]->setSuccessFlashMessage("Stránka byla úspěšně upravena.");
	}



	protected function createComponentForm($name)
	{
		$form = new PageForm($this, $name);
		$form->setEntityService($this->service);
		$form->setRedirect("default");
	}



	protected function createComponentGrid()
	{
		$grid = new Gridito\Grid;

		$grid->setModel($this->service->getGriditoModel());

		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("name", "Název")->setSortable(true);
		$grid->addColumn("url", "URL")->setSortable(true);
		$grid->addColumn("description", "Popis")->setSortable(true);
		$grid->addColumn("allowed", "Samostatný článek")->setSortable(true);

		$grid->addToolbarButton("Nová stránka", null, "plusthick")->setLink($this->link("add"));

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("Upravit", null, "pencil")
			->setLink(function ($page) use ($presenter) {
				return $presenter->link("edit", array("id" => $page->id));
			});

		$grid->addButton("Smazat", function ($entity) use ($service, $presenter, $grid) {
			$service->delete($entity);			
			$grid->flashMessage("Stránka byla úspěšně smazána.");
			$presenter->redirect("default");
		}, "closethick")
			->setConfirmationQuestion(function ($page) {
				return "Opravdu chcete smazat stránku '$page->name'?";
			});

		return $grid;
	}
	
}