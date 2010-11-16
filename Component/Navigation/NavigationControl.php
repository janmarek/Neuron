<?php

namespace Neuron;

/**
 * Navigation
 *
 * @author Jan Marek
 * @license MIT
 */
class NavigationControl extends BaseControl
{
	/** @var NavigationNode */
	private $current;

	/** @var bool */
	private $useHomepage = false;



	/**
	 * Set node as current
	 * @param NavigationNode node
	 */
	public function setCurrent(NavigationNode $node)
	{
		if (isset($this->current)) {
			$this->current->setCurrentFlag(false);
		}
		$this->current = $node->setCurrentFlag(true);
	}



	/**
	 * Add navigation node as a child
	 * @param string label
	 * @param string url
	 * @return NavigationNode added node
	 */
	public function add($label, $url = null)
	{
		return $this->getComponent("homepage")->add($label, $url);
	}



	/**
	 * Setup homepage
	 * @param string label
	 * @param string url
	 * @return NavigationNode
	 */
	public function setupHomepage($label, $url)
	{
		$this->useHomepage = true;
		return $this->getComponent("homepage")->setLabel($label)->setUrl($url);
	}



	/**
	 * Homepage factory
	 * @param string name
	 */
	protected function createComponentHomepage($name)
	{
		new NavigationNode($this, $name);
	}



	/**
	 * Render menu
	 * @param bool render children
	 * @param NavigationNode menu root
	 * @param bool render homepage
	 */
	public function renderMenu($renderChildren = true, $base = null, $renderHomepage = true)
	{
		$template = $this->createTemplate()->setFile(__DIR__ . "/menu.phtml");
		$template->homepage = $base ? $base : $this->getComponent("homepage");
		$template->useHomepage = $this->useHomepage && $renderHomepage;
		$template->renderChildren = $renderChildren;
		$template->children = $this->getComponent("homepage")->getComponents();
		$template->render();
	}



	/**
	 * Render full menu
	 */
	public function render()
	{
		$this->renderMenu();
	}



	public function getTemplateFilePath()
	{
		return __DIR__ . "/menu.phtml";
	}



	/**
	 * Render main menu
	 */
	public function renderMainMenu()
	{
		$this->renderMenu(false);
	}



	/**
	 * Render breadcrumbs
	 */
	public function renderBreadcrumbs()
	{
		if (empty($this->current)) {
			return;
		}

		$items = array();
		$node = $this->current;

		while ($node instanceof NavigationNode) {
			$parent = $node->getParent();
			if (!$this->useHomepage && !($parent instanceof NavigationNode)) {
				break;
			}

			array_unshift($items, $node);
			$node = $parent;
		}

		$template = $this->createTemplate()->setFile(__DIR__ . "/breadcrumbs.phtml");
		$template->items = $items;
		$template->render();
	}

}