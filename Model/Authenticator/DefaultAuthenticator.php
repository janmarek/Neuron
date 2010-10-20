<?php

namespace Neuron\Model;

use Nette\Security\Identity, Nette\Security\AuthenticationException;

class DefaultAuthenticator implements \Nette\Security\IAuthenticator
{
	/** @var \Model\UserService */
	private $userService;



	public function __construct($userService)
	{
		$this->userService = $userService;
	}

	
	
	/**
	 * Performs an authentication
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$user = $this->userService->findOneByUsername($username);

		if ($user) {
			if ($user->verifyPassword($password)) {
				return new Identity($user->getId(), "admin", array(
					"name" => $user->getName(),
					"surname" => $user->getSurname(),
					"mail" => $user->getMail(),
					"username" => $user->getUsername(),
				));
			} else {
				throw new AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
			}
		}

		throw new AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
	}

}
