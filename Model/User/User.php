<?php

namespace Neuron\Model;

/**
 * User entity
 *
 * @Entity
 * @Table(name="user")
 *
 * @author Jan Marek
 *
 * @property string $name
 * @property string $mail
 * @property string $surname
 */
class User extends BaseEntity
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/**
	 * @var string
	 * @Column
	 * @Validation({@NotBlank})
	 */
	private $name;

	/**
	 * @var string
	 * @Column
	 * @Validation({@NotBlank})
	 */
	private $surname;

	/**
	 * @var string
	 * @Column(unique=true)
	 * @Validation({@NotBlank, @Email})
	 */
	private $mail;

	/**
	 * @var string
	 * @Column
	 */
	private $password;

	/**
	 * @var string
	 * @Column
	 */
	private $salt;

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



	public function getSurname()
	{
		return $this->surname;
	}



	public function setSurname($surname)
	{
		$this->surname = $surname;
	}



	public function getMail()
	{
		return $this->mail;
	}



	public function setMail($mail)
	{
		$this->mail = $mail;
	}



	public function verifyPassword($password)
	{
		return $this->password === sha1($this->salt . $password);
	}



	public function setPassword($password)
	{
		$this->salt = md5(uniqid("", true));
		$this->password = sha1($this->salt . $password);
	}

	// </editor-fold>

}