<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Neuron\Form\ClipForm;

/**
 * Clip presenter
 *
 * @author Jan Marek
 */
class ClipPresenter extends \Neuron\Presenter\AdminModule\AdminPresenter
{
	/** @var \Neuron\Model\Clip\Service */
	private $service;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("ClipService");
	}



	public function renderDefault()
	{
		$this->template->title = "Výstřižky";
	}



	public function actionAdd($key = null)
	{
		$this->template->title = "Přidat výstřižek";
		$this["form"]->bindEntity($this->service->createBlank());
		if ($key) {
			$this["form"]["insertKey"]->setDefaultValue($key);
		}
		$this["form"]->setSuccessFlashMessage("Výstřižek byl vytvořen");
	}



	public function actionEdit($id)
	{
		$entity = $this->service->find($id);
		$this->template->title = "Upravit $entity->insertKey";
		$this["form"]->bindEntity($entity);
		$this["form"]->setSuccessFlashMessage("Výstřižek byl upraven");
	}



	protected function createComponentForm($name)
	{
		$form = new ClipForm($this, $name);
		$form->setEntityService($this->service);
		$form->setRedirect("default");
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->service->getFinder()->getGriditoModel());

		$grid->addColumn("insertKey", "Klíč")->setSortable(true);

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
				$presenter->flashMessage("Výstřižek byl smazán");
				$presenter->redirect("this");
			},
			"confirmationQuestion" => function ($entity) {
				return "Smazat výstřižek $entity->insertKey?";
			},
		));

		$grid->addToolbarButton("add", "Přidat", array(
			"icon" => "ui-icon-plusthick",
			"link" => $this->link("add"),
		));
	}
}