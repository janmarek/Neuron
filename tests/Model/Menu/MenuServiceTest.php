<?php

namespace Neuron\Tests\Model\Menu;

use Nette\Environment;
use Neuron\Model\Menu\Service;
use Neuron\Model\Menu\MenuItem;

class MenuServiceTest extends \Neuron\Testing\TestCase
{
	/**
	 * @var Neuron\Model\Menu\Service
	 */
	protected $object;



	public static function setUpBeforeClass()
	{
		$em = Environment::getService("Doctrine\ORM\EntityManager");
		$em->getConnection()->executeQuery("DELETE FROM menuitem");
	}



	protected function setUp()
	{
		$em = Environment::getService("Doctrine\ORM\EntityManager");
		$this->object = new Service($em);
	}



	protected function tearDown()
	{

	}



	public function testFetchEmptyTree()
	{
		$this->assertNull($this->object->fetchTree());
	}



	public function testCreateRootNodeAndFetchTree()
	{
		$root = $this->object->createRootNode(array(
			"name" => "Name",
			"url" => "/",
		));

		$tree = $this->object->fetchTree();

		$this->assertType("DoctrineExtensions\NestedSet\NodeWrapper", $tree);
		$this->assertSame("Name", $tree->getNode()->getName());
		$this->assertSame(array(), $tree->getChildren());

		return $root->getNode();
	}



	/**
	 * @depends testCreateRootNodeAndFetchTree
	 */
	public function testCreateNode(MenuItem $root)
	{
		$this->object->createNode(array(
			"name" => "Example",
			"url" => "http://www.example.org",
		), $root);

		$tree = $this->object->fetchTree();
		$this->assertEquals(1, count($tree->getChildren()));

		$node = $tree->getFirstChild()->getNode();
		$this->assertEquals("Example", $node->getName());

		return $node;
	}



	/**
	 * @depends testCreateNode
	 */
	public function testUpdateNode(MenuItem $node)
	{
		$this->object->updateNode($node, array("name" => "Example 2"));

		$tree = $this->object->fetchTree();
		$node = $tree->getFirstChild()->getNode();
		$this->assertEquals("Example 2", $node->getName());

		return $node;
	}



	/**
	 * @depends testUpdateNode
	 */
	public function testDeleteNode(MenuItem $node)
	{
		$this->object->deleteNode($node);

		$tree = $this->object->fetchTree();
		$this->assertSame(array(), $tree->getChildren());
	}

}