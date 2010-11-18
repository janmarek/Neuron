<?php

namespace Neuron;

use DoctrineExtensions\NestedSet;

/**
 * Menu
 *
 * @author Jan Marek
 */
class MenuControl extends NavigationControl
{
	protected function init()
	{
		$tree = $this->getService("MenuService")->fetchTree();
		$this->addMenuNode($this, $tree, true);
	}

	private function addMenuNode($parent, NestedSet\NodeWrapper $node, $homepage)
	{
		$menuItem = $node->getNode();

		$name = $menuItem->getName();
		$url = $menuItem->getUrl();

		if ($homepage) {
			$newNode = $parent->setupHomepage($name, $url);
		} else {
			$newNode = $parent->add($name, $url);
		}

		$newNode->setAsCurrent($menuItem->isCurrent());
		$newNode->setOpenInNewWindow($menuItem->isExternal());

		foreach ($node->getChildren() as $child) {
			$this->addMenuNode($newNode, $child, false);
		}
	}

}