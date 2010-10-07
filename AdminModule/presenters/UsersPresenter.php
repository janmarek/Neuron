<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Neuron\Form\UserForm;

class UsersPresenter extends AdminPresenter
{
	/** @var \Neuron\Model\UserService */
	private $service;

	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("UserService");
	}

	protected function createComponentGrid()
	{
		$grid = new Grid;

		$grid->setModel($this->service->getGriditoModel());

		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("mail", "E-mail")->setSortable(true);

		$grid->addToolbarButton("Nový uživatel", null, "plusthick")->setLink($this->link("add"));

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("Upravit", null, "pencil")
			->setLink(function ($entity) use ($presenter) {
				return $presenter->link("edit", array("id" => $entity->id));
			});

		$grid->addButton("Smazat", function ($entity) use ($service, $presenter, $grid) {
			$service->delete($entity);
			$grid->flashMessage("Uživatel byl úspěšně smazán.");
			$presenter->redirect("default");
		}, "closethick")
			->setConfirmationQuestion(function ($entity) {
				return "Opravdu chcete smazat uživatele '$entity->name'?";
			});

		return $grid;
	}



	protected function createComponentAddForm($name)
	{
		$form = new UserForm($this, $name);
		$form->bindEntity($this->service->createBlank());
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage("Uživatel byl úspěšně založen.");
		$form->setRedirectUri($this->link("default"));
	}



	protected function createComponentEditForm($name)
	{
		$form = new UserForm($this, $name);
		$form->bindEntity($this->service->find($this->getParam("id")));
		$form->setEntityService($this->service);
		$form->setSuccessFlashMessage("Uživatel byl úspěšně založen.");
		$form->setRedirectUri($this->link("default"));
	}
}