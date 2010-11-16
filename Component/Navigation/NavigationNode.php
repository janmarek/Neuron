<?php

namespace Neuron;

/**
 * Navigation node
 *
 * @author Jan Marek
 * @license MIT
 *
 * @property string $label
 * @property string $url
 * @property-read bool $current
 * @property bool $openInNewWindow
 */
class NavigationNode extends \Nette\ComponentContainer
{
	/** @var string */
	private $label;

	/** @var string */
	private $url;

	/** @var bool */
	private $isCurrent = false;

	/** @var bool */
	private $openInNewWindow = false;



	/**
	 * Get label
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}



	/**
	 * Set label
	 * @param string label
	 * @return NavigationNode
	 */
	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}



	/**
	 * Get URL
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}



	/**
	 * Set URL
	 * @param string url
	 * @return NavigationNode
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}



	/**
	 * Is current
	 * @return bool
	 */
	public function isCurrent()
	{
		return $this->isCurrent;
	}



	/**
	 * Set current flag
	 * @param bool is current
	 * @return NavigationNode
	 */
	public function setCurrentFlag($isCurrent)
	{
		$this->isCurrent = $isCurrent;
		return $this;
	}



	/**
	 * Open in new window
	 * @return bool
	 */
	public function getOpenInNewWindow()
	{
		return $this->openInNewWindow;
	}



	/**
	 * Set open in new window
	 * @param bool open in new window
	 * @return NavigationNode
	 */
	public function setOpenInNewWindow($openInNewWindow)
	{
		$this->openInNewWindow = $openInNewWindow;
		return $this;
	}



	/**
	 * Add navigation node as a child
	 * @param string label
	 * @param string url
	 * @return NavigationNode added node
	 */
	public function add($label, $url)
	{
		$navigationNode = new self;
		$navigationNode->label = $label;
		$navigationNode->url = $url;

		static $counter;
		$this->addComponent($navigationNode, ++$counter);

		return $navigationNode;
	}



	/**
	 * Set as current
	 * @param bool set as current
	 * @return NavigationNode
	 */
	public function setAsCurrent($setAsCurrent)
	{
		if ($setAsCurrent) {
			$this->lookup("Neuron\NavigationControl")->setCurrent($this);
		}

		return $this;
	}

}