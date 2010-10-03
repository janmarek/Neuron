<?php

namespace Model;

use Nette\Environment;

/**
 * Base model entity
 *
 * @property-read int $id
 * @property-read string $cacheKey
 *
 * @MappedSuperclass
 */
abstract class BaseEntity extends \Nette\Object
{
	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 * @var int
	 */
	private $id;



	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}



	/**
	 * @return string
	 */
	public function getCacheKey()
	{
		return get_class($this) . "#" . $this->id;
	}

}