<?php

namespace Neuron\Model\User;

/**
 * User entity
 *
 * @Entity
 * @Table(name="user")
 *
 * @author Jan Marek
 */
class User extends \Neuron\Model\BaseEntity
{
	/**
	 * @var string
	 * @Column
	 * @validation:NotBlank(message="Jméno není vyplněné.")
	 */
	private $name;

	/**
	 * @var string
	 * @Column
	 * @validation:NotBlank(message="Uživatelské jméno není vyplněné.")
	 */
	private $surname;

	/**
	 * @var string
	 * @Column(unique=true)
	 * @validation:NotBlank(message="E-mail není vyplněn.")
	 * @validation:Email(message="E-mail nemá správný formát.")
	 * @validation:Unique(message="Jméno není unikátní.")
	 */
	private $mail;

	/**
	 * @var string
	 * @Column(unique=true)
	 * @validation:NotBlank(message="Uživatelské jméno není vyplněné.")
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
	 * @validation:NotBlank(message="Heslo není vyplněné.")
	 */
	private $password;



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

}