<?php

namespace Neuron\Model;

use Nette\String;

/**
 * Page entity
 *
 * @MappedSuperClass
 *
 * @property string $name
 * @property string $url
 * @property string $description
 * @property string $text
 */
abstract class Page extends BaseEntity
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/**
	 * @var string
	 * @Column(unique=true)
	 * @Validation({@NotBlank})
	 */
	private $name;

	/**
	 * @var string
	 * @Column(unique=true)
	 * @Validation({@NotBlank})
	 */
	private $url;

	/**
	 * @var string
	 * @Column
	 * @Validation({@NotBlank})
	 */
	private $description;

	/**
	 * @var string
	 * @Column(type="text")
	 * @Validation({@NotBlank})
	 */
	private $text;

	/**
	 * @var bool
	 * @Column(type="boolean")
	 */
	private $allowed;

	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="getters & setters">

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

	// </editor-fold>

}