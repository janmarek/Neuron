<?php

namespace Neuron\Model\Clip;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Clip entity
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name="clip")
 */
class Clip extends \Neuron\Model\BaseEntity
{
	/**
	 * @Column(unique=true)
	 * @validation:NotBlank(message="Klíč pro vložení výstřižku není vyplněn.")
	 * @validation:Unique(message="Klíč pro vložení není unikátní.")
	 */
	private $insertKey;

	/**
	 * @Column(type="text")
	 * @validation:NotBlank(message="Text není vyplněn.")
	 */
	private $text;



	public function setText($text)
	{
		$this->text = $text;
	}



	public function getText()
	{
		return $this->text;
	}



	public function setInsertKey($insertKey)
	{
		$this->insertKey = $insertKey;
	}



	public function getInsertKey()
	{
		return $this->insertKey;
	}

}