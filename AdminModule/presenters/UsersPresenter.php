<?php

namespace Neuron\Presenter\AdminModule;

use Gridito\Grid;
use Neuron\Form\UserForm;

class UsersPresenter extends AdminPresenter
{
	/** @var Neuron\Model\UserService */
	private $service;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("UserService");
	}



	public function actionDefault()
	{
		$this->template->title = "Uživatelé";
	}



	public function actionAdd()
	{
		$this->template->title = "Přidat uživatele";
		$this["form"]->bindEntity($this->service->createBlank());
		$this["form"]->setSuccessFlashMessage("Uživatel byl úspěšně založen.");
	}


	
	public function actionEdit($id)
	{
		$user = $this->service->find($id);
		$this->template->title = "Upravit uživate $user->name $user->surname";
		$this["form"]->bindEntity($user);
		$this["form"]->setSuccessFlashMessage("Uživatel byl úspěšně upraven.");
	}



	protected function createComponentForm($name)
	{
		$form = new UserForm($this, $name);
		$form->setEntityService($this->service);
		$form->setRedirect("default");
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->service->getFinder()->getGriditoModel());
		
		// columns

		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("surname", "Příjmení")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("mail", "E-mail")->setSortable(true);

		// buttons

		$presenter = $this;
		$service = $this->service;

		$grid->addButton("edit", "Upravit", array(
			"icon" => "pencil",
			"link" => function ($entity) use ($presenter) {
				return $presenter->link("edit", array("id" => $entity->id));
			},
		));

		$grid->addButton("delete", "Smazat", array(
			"icon" => "closethick",
			"handler" => function ($entity) use ($service, $presenter) {
				$service->delete($entity);
				$presenter->flashMessage("Uživatel byl úspěšně smazán.");
				$presenter->redirect("default");
			},
			"confirmationQuestion" => function ($entity) {
				return "Opravdu chcete smazat uživatele '$entity->name'?";
			},
		));
		
		// toolbar

		$grid->addToolbarButton("newuser", "Nový uživatel", array(
			"icon" => "plusthick",
			"link" => $this->link("add"),
		));
	}
	
}