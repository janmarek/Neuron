<?php

namespace Neuron\Model\Tag;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tag entity
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name="tag")
 */
class Tag extends \Neuron\Model\BaseEntity
{
	/**
	 * @Column
	 * @validation:NotBlank(message="Jméno není vyplněné.")
	 */
	private $name;

	/**
	 * @Column
	 * @validation:NotBlank(message="URL není vyplněná.")
	 */
	private $url;

	/**
	 * @Column(type="integer")
	 */
	private $itemCount = 0;



	public function setName($name)
	{
		$this->name = $name;
		$this->url = \Nette\String::webalize($name);
	}



	public function getName()
	{
		return $this->name;
	}



	public function getUrl()
	{
		return $this->url;
	}



	public function getItemCount()
	{
		return $this->itemCount;
	}



	public function increase()
	{
		$this->itemCount++;
	}



	public function decrease()
	{
		if ($this->itemCount > 0) {
			$this->itemCount--;
		}
	}

}