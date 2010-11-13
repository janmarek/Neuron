<?php

namespace Neuron\Model;

use Nette\Environment;
use Nette\Web\HttpRequest, Nette\Web\UriScript;

/**
 * Menu item
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name="menuitem")
 */
class MenuItem extends BaseEntity implements \DoctrineExtensions\NestedSet\Node
{
	/** @Column(nullable=true) */
	private $name;

	/** @Column(nullable=true) */
	private $destination;

	/** @Column(type="array") */
	private $params = array();

	/** @Column(nullable=true) */
	private $url;

	/** @Column(type="integer") */
	private $lft;

	/** @Column(type="integer") */
	private $rgt;



	public function setName($name)
	{
		$this->name = $name;
	}



	public function getName()
	{
		return $this->name;
	}



	public function getUrl()
	{
		if (isset($this->destination)) {
			return Environment::getApplication()->getPresenter()->link($this->destination, $this->params);
		} else {
			return $this->url;
		}
	}



	public function setUrl($url)
	{
		$httpRequest = new HttpRequest(new UriScript($url));
		$presenterRequest = Environment::getApplication()->getRouter()->match($httpRequest);

		if ($presenterRequest === null) {
			$this->url = $url;
			
		} else {
			$presenter = $presenterRequest->getPresenterName();
			$params = $presenterRequest->getParams();
			$action = isset($params["action"]) ? $params["action"] : "default";
			unset($params["action"]);
			
			$this->destination = "$presenter:$action";
			$this->params = $params;
		}
	}

	
	// Node interface


	public function __toString()
	{
		return $this->name;
	}



//	public function getId()
//	{
//		return parent::getId();
//	}



	public function getLeftValue()
	{
		return $this->lft;
	}



	public function getRightValue()
	{
		return $this->rgt;
	}



	public function setLeftValue($lft)
	{
		$this->lft = $lft;
	}



	public function setRightValue($rgt)
	{
		$this->rgt = $rgt;
	}

}