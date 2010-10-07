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
		$this->service = $this->getService("PageService");
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



	protected function createComponentAddForm($name)
	{
		$form = new PageForm($this, $name);
		$form->bindEntity($this->service->createBlank());
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage("Stránka byla úspěšně vložena.");
		$form->setRedirectUri($this->link("default"));
	}



	protected function createComponentEditForm($name)
	{
		$form = new PageForm($this, $name);
		$form->bindEntity($this->service->find($this->getParam("id")));
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage("Stránka byla úspěšně upravena.");
		$form->setRedirectUri($this->link("default"));
	}
}