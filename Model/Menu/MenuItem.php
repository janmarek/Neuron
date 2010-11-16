<?php

namespace Neuron\Model;

use Nette\Environment;
use Nette\Web\HttpRequest, Nette\Web\UriScript;
use Nette\String;

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



	public function isCurrent()
	{
		if (empty($this->destination)) {
			return false;
		}

		$presenter = Environment::getApplication()->getPresenter();
		$presenter->link(":$this->destination", $this->params);
		return $presenter->getLastCreatedRequestFlag("current");
	}



	public function getUrl()
	{
		if (isset($this->destination)) {
			return Environment::getApplication()->getPresenter()->link("//:$this->destination", $this->params);
		} else {
			return $this->url;
		}
	}



	public function setUrl($url)
	{
		$uriScript = new UriScript($url);
		$uriScript->setScriptPath(Environment::getHttpRequest()->getUri()->getScriptPath());

		$httpRequest = new HttpRequest($uriScript);
		$presenterRequest = Environment::getApplication()->getRouter()->match($httpRequest);

		if ($presenterRequest === null || !String::startsWith($url, Environment::getVariable("baseUri"))) {
			$this->url = $url ?: null;
			$this->destination = null;
			$this->params = array();

		} else {
			$presenter = $presenterRequest->getPresenterName();
			$params = $presenterRequest->getParams();
			$action = isset($params["action"]) ? $params["action"] : "default";
			$module = isset($params["module"]) ? $params["module"] . ":" : "";
			unset($params["action"]);

			$this->destination = "$module$presenter:$action";
			$this->params = $params;
			$this->url = null;
		}
	}


	// Node interface


	public function __toString()
	{
		return $this->name;
	}



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