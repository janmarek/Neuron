<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Neuron\Form\DictionaryItemForm;

abstract class DictionaryPresenter extends AdminPresenter
{
	/** @var Neuron\Model\Service */
	protected $service;



	protected function createComponentForm($name)
	{
		$form = new DictionaryItemForm($this, $name);
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage("Položka byla úspěšně uložena.");
		$form->setRedirect("default");
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->service->getFinder()->getGriditoModel());

		// columns

		$grid->addColumn("id", "ID")->setSortable(true);
		$grid->addColumn("name", "Název")->setSortable(true);

		// buttons

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("edit", "Upravit", array(
			"icon" => "ui-icon-pencil",
			"link" => function ($item) use ($presenter) {
				return $presenter->link("edit", array("id" => $item->id));
			},
		));

		$grid->addButton("delete", "Smazat", array(
			"icon" => "ui-icon-closethick",
			"handler" => function ($item) use ($service, $presenter) {
				$service->delete($item);
				$presenter->flashMessage("Položka byla úspěšně smazána.");
				$presenter->redirect("default");
			},
			"confirmationQuestion" => function ($item) {
				return "Opravdu chcete smazat položku '$item->name'?";
			},
		));

		// toolbar

		$grid->addToolbarButton("new", "Nová položka", array(
			"icon" => "ui-icon-plusthick",
			"link" => $this->link("add"),
		));
	}
	
}