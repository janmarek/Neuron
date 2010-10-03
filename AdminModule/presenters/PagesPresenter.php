<?php

namespace Neuron\Presenter\AdminModule;

use Gridito, Model\Service;

class PagesPresenter extends AdminPresenter
{
	protected function createComponentGrid()
	{
		$grid = new Gridito\Grid;

		$grid->setModel(new Gridito\DoctrineModel($this->getService("Doctrine\ORM\EntityManager"), "Neuron\Model\Page"));

		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("name", "Název")->setSortable(true);
		$grid->addColumn("url", "URL")->setSortable(true);
		$grid->addColumn("description", "Popis")->setSortable(true);
		$grid->addColumn("allowed", "Samostatný článek")->setSortable(true);

		$grid->addToolbarButton("Nová stránka", null, "plusthick")->setLink($this->link("add"));

		$presenter = $this;

		$grid->addButton("Upravit", null, "pencil")
			->setLink(function ($page) use ($presenter) {
				return $presenter->link("edit", array("id" => $page->id));
			});

		$grid->addButton("Smazat", function ($entity) use ($presenter, $grid) {
			$em = $presenter->getEntityManager();
			$em->remove($entity);
			$em->flush();
			
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
		$form = new \CreatePageForm($this, $name);
	}



	protected function createComponentEditForm($name)
	{
		$form = new \EditPageForm($this, $name);

		if (!$form->submitted) {
			$page = $this->getService("PageService")->find($this->getParam("id"), Service::MODE_ARRAY);
			$form->setDefaults($page);
		}
	}
}