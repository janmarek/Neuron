<?php

namespace Neuron\Presenter\AdminModule;

use Nette\Web\Html;
use Neuron\Form\MenuItemForm;
use Gridito\Grid;

/**
 * Menu presenter
 *
 * @author Jan Marek
 */
class MenuPresenter extends AdminPresenter
{
	/** @var \Neuron\Model\MenuService */
	private $service;



	public function startup()
	{
		parent::startup();
		$this->service = $this->getService("MenuService");
	}



	public function actionDefault()
	{
		$tree = $this->service->fetchTree();

		if ($tree === null) {
			$this->redirect("createRootNode");
		}

		$this->template->title = "Menu";
	}



	public function actionCreateRootNode()
	{
		$service = $this->service;

		if ($service->fetchTree() !== null) {
			$this->redirect("default");
		}

		$this["form"]->setHandler(function ($values) use ($service) {
			$service->createRootNode($values);
		});

		$this["form"]->setSuccessFlashMessage("Kořenový uzel menu byl vytvořen.");

		$this->template->title = "Vytvořit odkaz na úvodní stránku";
	}



	public function actionAdd($id)
	{
		$service = $this->service;
		$parent = $service->find($id)->getNode();

		$this["form"]->setHandler(function ($values) use ($service, $parent) {
			$service->createNode($values, $parent);
		});

		$this["form"]->setSuccessFlashMessage("Položka menu byla vytvořena.");

		$this->template->title = "Vytvořit odkaz";
	}



	public function actionEdit($id)
	{
		$service = $this->service;
		$entity = $service->find($id)->getNode();

		$this["form"]->setDefaults(array(
			"name" => $entity->getName(),
			"url" => $entity->getUrl(),
		));

		$this["form"]->setHandler(function ($values) use ($service, $entity) {
			$service->updateNode($entity, $values);
		});

		$this["form"]->setSuccessFlashMessage("Položka menu byla upravena.");

		$this->template->title = "Upravit odkaz";
	}

	

	protected function createComponentForm($name)
	{
		$form = new MenuItemForm($this, $name);
		$form->setRedirect("default");
	}



	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->service->getGriditoModel());

		$grid->addColumn("name", "Popis", array(
			"renderer" => function ($node) {
				echo str_repeat("&nbsp;", $node->getLevel() * 4);
				echo Html::el("a")->href($node->getNode()->getUrl())->setText($node->getNode()->getName());
			}
		));
		
		$presenter = $this;

		$grid->addButton("add", "Přidat", array(
			"icon" => "ui-icon-plusthick",
			"link" => function ($node) use ($presenter) {
				return $presenter->link("add", $node->getId());
			},
			"showText" => false,
		));
			
		$grid->addButton("edit", "Upravit", array(
			"icon" => "ui-icon-pencil",
			"link" => function ($node) use ($presenter) {
				return $presenter->link("edit", $node->getId());
			},
			"showText" => false,
		));

		$grid->addButton("delete", "Smazat", array(
			"icon" => "ui-icon-closethick",
			"handler" => function ($node) use ($grid) {
				$node->delete();
				$grid->flashMessage("Položka menu byla smazána.");
			},
			"confirmationQuestion" => function ($node) {
				return "Opravdu smazat položku " . $node->getNode()->getName() . "?";
			},
			"visible" => function ($node) {
				return !$node->isRoot();
			},
			"showText" => false,
			"ajax" => true,
		));

		$grid->addButton("up", "Nahoru", array(
			"icon" => "ui-icon-arrowthick-1-n",
			"handler" => function ($node) use ($grid) {
				$node->moveAsPrevSiblingOf($node->getPrevSibling());
				$grid->flashMessage("Odkaz přesunut.");
			},
			"visible" => function ($node) {
				return $node->getPrevSibling() !== null;
			},
			"showText" => false,
			"ajax" => true,
		));

		$grid->addButton("down", "Dolů", array(
			"icon" => "ui-icon-arrowthick-1-s",
			"handler" => function ($node) use ($grid) {
				$node->moveAsNextSiblingOf($node->getNextSibling());
				$grid->flashMessage("Odkaz přesunut.");
			},
			"visible" => function ($node) {
				return $node->getNextSibling() !== null;
			},
			"showText" => false,
			"ajax" => true,
		));
	}

}