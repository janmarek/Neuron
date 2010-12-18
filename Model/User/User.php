<?php

namespace Neuron\Model\User;

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
class User extends \Neuron\Model\BaseEntity
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/**
	 * @var string
	 * @Column
	 * @validation:NotBlank
	 */
	private $name;

	/**
	 * @var string
	 * @Column
	 * @validation:NotBlank
	 */
	private $surname;

	/**
	 * @var string
	 * @Column(unique=true)
	 * @validation:Validation({
	 *   @validation:NotBlank,
	 *   @validation:Email
	 * })
	 */
	private $mail;

	/**
	 * @var string
	 * @Column(unique=true)
	 * @validation:NotBlank
	 */
	private $username;

	/**
	 * @var string
	 * @Column(nullable=true)
	 */
	private $phone;

	/**
	 * @var string
	 * @Column
	 * @validation:NotBlank
	 */
	private $password;

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



	public function getUsername()
	{
		return $this->username;
	}



	public function setUsername($username)
	{
		$this->username = $username;
	}



	public function verifyPassword($password)
	{
		list($salt, $hash, $hashFunction) = explode('$', $this->password);
		return $hash === $hashFunction($salt . $password);
	}



	public function setPassword($password, $hashFunction = "sha1")
	{
		$salt = md5(uniqid("", true));
		$hash = $hashFunction($salt . $password);
		$this->password = $salt . '$' . $hash . '$' . $hashFunction;
	}



	public function setPhone($phone)
	{
		$this->phone = $phone ?: null;
	}



	public function getPhone()
	{
		return $this->phone;
	}

	// </editor-fold>

}