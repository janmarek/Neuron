<?php


class MyAuthenticator extends Nette\Object implements Nette\Security\IAuthenticator
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
		$username = $credentials[self::USERNAME];
		$password = $credentials[self::PASSWORD];

		// authenticate admin user
		$user = $this->userService->findOneByUsername($username);

		if ($user) {
			if ($user->verifyPassword($password)) {
				return new Nette\Security\Identity($user->getId(), "admin", $this->userService->toArray($user));
			} else {
				throw new Nette\Security\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
			}
		}

		throw new Nette\Security\AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
	}

}
