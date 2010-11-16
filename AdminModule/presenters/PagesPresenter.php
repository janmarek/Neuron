<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
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



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->service->getFinder()->getGriditoModel());

		// columns

		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("name", "Název")->setSortable(true);
		$grid->addColumn("url", "URL")->setSortable(true);
		$grid->addColumn("description", "Popis")->setSortable(true);
		$grid->addColumn("allowed", "Samostatný")->setSortable(true);

		// buttons

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("edit", "Upravit", array(
			"icon" => "ui-icon-pencil",
			"link" => function ($page) use ($presenter) {
				return $presenter->link("edit", array("id" => $page->id));
			},
			"showText" => false,
		));

		$grid->addButton("delete", "Smazat", array(
			"icon" => "ui-icon-closethick",
			"handler" => function ($entity) use ($service, $presenter) {
				$service->delete($entity);
				$presenter->flashMessage("Stránka byla úspěšně smazána.");
				$presenter->redirect("default");
			},
			"confirmationQuestion" => function ($page) {
				return "Opravdu chcete smazat stránku '$page->name'?";
			},
			"showText" => false,
		));

		// toolbar

		$grid->addToolbarButton("new", "Nová stránka", array(
			"icon" => "ui-icon-plusthick",
			"link" => $this->link("add"),
		));
	}
	
}