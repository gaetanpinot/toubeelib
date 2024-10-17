<?php
namespace toubeelib\providers\auth;

use DI\Container;
use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\ServiceAuthBadPasswordException;
use toubeelib\core\services\ServiceAuthInterface;

class JWTAuthnProvider implements AuthnProviderInterface{
	protected ServiceAuthInterface $serviceAuth;
	protected JWTManager $jwtManager;
	public function __construct(Container $co)
	{
		$this->serviceAuth = $co->get(ServiceAuthInterface::class);
		$this->jwtManager = $co->get(JWTManager::class);
	}
	public function register(CredentialsDTO $credentials): void
	{
	}

	public function signin(CredentialsDTO $credentials): AuthDTO
	{
		$user = $this->serviceAuth->byCredentials($credentials);
		$token = $this->jwtManager->createAcessToken($user);
		$authdto = new AuthDTO($user->id,$user->role);
		$authdto->setAtoken($token);
		return $authdto;

	}

	public function refresh(AuthDTO $credentials): AuthDTO
	{
	}

	public function getSignedInUser(AuthDTO $authDto): AuthDTO
	{
		$token = $this->jwtManager->decodeToken($authDto->atoken);
		$authDto->setId($token['sub']);
		$authDto->setRole($token['role']);
		return $authDto;

	}

}
