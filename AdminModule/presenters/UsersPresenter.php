<?php

namespace AdminModule;

use Gridito, Model, Nette\Debug;

class UsersPresenter extends AdminPresenter
{
	protected function createComponentGrid()
	{
		$grid = new Gridito\Grid;

		$grid->setModel(new Gridito\DoctrineModel($this->getEntityManager(), "Model\User"));

		$grid->addColumn("name", "Jméno")->setSortable(true);
		$grid->addColumn("username", "Uživatelské jméno")->setSortable(true);
		$grid->addColumn("mail", "E-mail")->setSortable(true);

		$grid->addToolbarButton("Nový uživatel", null, "plusthick")->setLink($this->link("add"));

		$presenter = $this;

		$grid->addButton("Upravit", null, "pencil")
			->setLink(function ($entity) use ($presenter) {
				return $presenter->link("edit", array("id" => $entity->id));
			});

		$grid->addButton("Smazat", function ($entity) use ($presenter, $grid) {
			$em = $presenter->getEntityManager();
			$em->remove($entity);
			$em->flush();

			$grid->flashMessage("Uživatel byl úspěšně smazán.");
			$presenter->redirect("default");
		}, "closethick")
			->setConfirmationQuestion(function ($entity) {
				return "Opravdu chcete smazat uživatele '$entity->name'?";
			});

		return $grid;
	}



	private function createFormBase($name, $new)
	{
		$form = new BaseForm($this, $name);

		if (!$new) {
			$form->addHidden("id");
		}

		$form->addText("name")
			->setRequired("Vyplňte heslo.");
		$form->addText("username")
			->setRequired("Vyplňte heslo.");
		$form->addText("mail");
		$form->addText("password");

		if ($new) {
			$form["password"]->setRequired("Vyplňte heslo.");
		}

		$form->addTextArea("text")
			->setAttribute("class", "texyla");

		$form->addSubmit("s");

		return $form;
	}


	protected function createComponentAddForm($name)
	{
		$form = $this->createFormBase($name, true);
		$form->onSubmit[] = array($this, "addForm_submit");
	}


	public function addForm_submit(BaseForm $form)
	{
		try {
			$values = $form->values;

			$user = new Model\User;
			$user->setMail($values["mail"]);
			$user->setName($values["name"]);
			$user->setPassword($values["password"]);
			$user->setText($values["text"]);
			$user->setUsername($values["username"]);

			$this->getEntityManager()->persist($user);
			$this->getEntityManager()->flush();

			$this->flashMessage("Uživatel byl úspěšně založen.");
			$this->redirect("default");
		} catch (\Exception $e) {
			if ($e instanceof \Nette\Application\AbortException) {
				throw $e;
			}

			Debug::log($e);
			$form->addError("Uživatele se nepodařilo uložit.");
		}
	}


	protected function createComponentEditForm($name)
	{
		$form = $this->createFormBase($name, false);
		$form->onSubmit[] = array($this, "editForm_submit");
		
		if (!$form->submitted) {
			$form->setDefaults($this->getEntityManager()->find("Model\User", $this->getParam("id"))->toArray());
		}
	}


	public function editForm_submit(BaseForm $form)
	{
		try {
			$values = $form->values;

			$user = $this->getEntityManager()->find("Model\User", $values["id"]);
			$user->setMail($values["mail"]);
			$user->setName($values["name"]);
			if ($values["password"]) {
				$user->setPassword($values["password"]);
			}
			$user->setText($values["text"]);
			$user->setUsername($values["username"]);

			$this->getEntityManager()->flush();

			$this->flashMessage("Uživatel byl úspěšně upraven.");
			$this->redirect("default");
		} catch (\Exception $e) {
			if ($e instanceof \Nette\Application\AbortException) {
				throw $e;
			}

			Debug::log($e);
			$form->addError("Uživatele se nepodařilo uložit.");
		}
	}
}