<?php

namespace Neuron\Application;

use Nette\Application\PresenterRequest;
use Nette\Environment;
use Nette\Web\Uri;
use Nette\Web\IHttpRequest;

/**
 * Seo router
 *
 * @author Jan Marek
 */
class SeoRouter extends \Nette\Object implements \Nette\Application\IRouter
{
	private $presenter;

	private $prefix;

	private $data;

	private $itemCallback;

	private $action;



	public function __construct($presenter, $action, $prefix, $itemCallback)
	{
		$this->presenter = $presenter;
		$this->action = $action;
		$this->prefix = $prefix;
		$this->itemCallback = $itemCallback;
	}



	private function getData()
	{
		if (empty($this->data)) {
			$this->data = call_user_func($this->itemCallback);
		}

		return $this->data;
	}



	public function constructUrl(PresenterRequest $appRequest, Uri $uri)
	{
		if ($appRequest->getPresenterName() !== $this->presenter) {
			return null;
		}

		$params = $appRequest->getParams();

		if (empty($params["id"]) || $params["action"] != $this->action) {
			return null;
		}

		$data = $this->getData();

		if (empty($data[$params["id"]])) {
			return null;
		}

		$uri = $uri->baseUri . $this->prefix . $data[$params["id"]];

		unset($params["id"], $params["action"], $params["language"]);

		$query = http_build_query($params, '', '&');
		if ($query !== '') $uri .= '?' . $query;

		return $uri;
	}


	
	public function match(IHttpRequest $httpRequest)
	{
		$path = substr($httpRequest->getUri()->getAbsoluteUri(), strlen(Environment::getVariable("baseUri")));

		foreach ($this->getData() as $id => $url) {
			if ($this->prefix . $url == $path || $this->prefix . $url . "/" == $path) {
				$params = $httpRequest->getQuery();
				$params["id"] = $id;
				$params["action"] = $this->action;

				return new PresenterRequest(
					$this->presenter,
					$httpRequest->getMethod(),
					$params,
					$httpRequest->getPost(),
					$httpRequest->getFiles(),
					array('secured' => $httpRequest->isSecured())
				);
			}
		}

		return null;
	}
}