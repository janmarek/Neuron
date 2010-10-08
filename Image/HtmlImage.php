<?php

namespace Neuron\Image;

use Nette\Environment;
use Nette\String;
use Nette\Web\Html;

/**
 * Html image
 *
 * @author Jan Marek
 */
class HtmlImage extends \Nette\Object
{
	/** @var string */
	private $path;

	/** @var string */
	private $alt = "";

	/** @var int */
	private $width;

	/** @var int */
	private $height;

	/** @var \Nette\Web\Html */
	private $html;


	
	public function __construct($path) {
		$this->path = realpath($path);

		if (!$this->path) {
			throw new \FileNotFoundException("File '$path' does not exist.");
		}

		$imageSize = @getimagesize($path);

		if (!$imageSize) {
			throw new \InvalidStateException("File '$path' is not image.");
		}

		$this->width = $imageSize[0];
		$this->height = $imageSize[1];
	}



	public function getFilePath()
	{
		return $this->path;
	}

	

	/**
	 * Get html object
	 * @return \Nette\Web\Html
	 */
	public function getHtml()
	{
		if (empty($this->html)) {
			$this->html = Html::el("img", array(
				"src" => $this->getSrc(),
				"width" => $this->getWidth(),
				"height" => $this->getHeight(),
				"alt" => $this->getAlt(),
			));
		}
		
		return $this->html;
	}



	public function __toString()
	{
		return (string) $this->getHtml();
	}



	public function getSrc()
	{
		$src = rtrim(Environment::getVariable('baseUri'), '/') . substr($this->path, strlen(WWW_DIR));
		$src = strtr($src, "\\", "/");

		return $src;
	}



	public function getWidth()
	{
		return $this->width;
	}



	public function getHeight()
	{
		return $this->height;
	}



	public function getAlt()
	{
		return $this->alt;
	}



	public function setAlt($alt)
	{
		$this->alt = $alt;
		return $this;
	}

}