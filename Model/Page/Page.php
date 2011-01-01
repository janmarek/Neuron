<?php

namespace Neuron\Model\Page;

use Nette\String;

/**
 * Page entity
 *
 * @MappedSuperClass
 */
abstract class Page extends \Neuron\Model\BaseEntity
{
	/**
	 * @Column
	 * @validation:NotBlank(message="Jméno není vyplněné.")
	 */
	private $name;

	/**
	 * @Column(unique=true)
	 * @validation:NotBlank(message="URL není vyplněna.")
	 * @validation:Unique(message="URL není unikátní.")
	 */
	private $url;

	/**
	 * @Column
	 */
	private $description;

	/**
	 * @Column(type="text")
	 */
	private $text;

	/**
	 * @Column(type="boolean")
	 */
	private $allowed;



	public function getName()
	{
		return $this->name;
	}



	public function setName($name)
	{
		$this->name = $name;
	}



	public function getUrl()
	{
		return $this->url;
	}



	public function setUrl($url)
	{
		$this->url = String::webalize($url);
	}



	public function getDescription()
	{
		return $this->description;
	}



	public function setDescription($description)
	{
		$this->description = $description;
	}



	public function getText()
	{
		return $this->text;
	}



	public function setText($text)
	{
		$this->text = $text;
	}



	public function getAllowed()
	{
		return $this->allowed;
	}



	public function setAllowed($allowed)
	{
		$this->allowed = (bool) $allowed;
	}

}