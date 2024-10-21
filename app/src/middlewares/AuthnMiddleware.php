<?php
namespace toubeelib\middlewares;

use DI\Container;
use Monolog\Logger;
use PHPUnit\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use toubeelib\providers\auth\AuthInvalidException;
use toubeelib\providers\auth\AuthnProviderInterface;

class AuthnMiddleware implements MiddlewareInterface{

	protected AuthnProviderInterface $authProvider;
	protected Logger $loger;
	
	public function __construct(Container $co)
	{
		$this->authProvider = $co->get(AuthnProviderInterface::class);
		$this->loger = $co->get(Logger::class)->withName("AutnhMiddleware");
	}

	public function process(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
	{
		$path = $rq->getUri()->getPath();
		if($path=="/signin"){
			$rs = $next->handle($rq);
			return $rs;
		}
		if(!$rq->hasHeader("Authorization")){
		foreach($rq->getHeaders() as $s){
			$this->loger->error($s[0]);
			}

			throw new HttpUnauthorizedException($rq, "Authorization manquante, veuillez vous enregistrer");
		}
		try{
			$token = $rq->getHeader("Authorization")[0];
			$token = sscanf($token, "Bearer %s");
			if($token == null){
				throw new Exception("Mauvais token");
			}
			$token = $token[0];
			$user = $this->authProvider->getSignedInUser($token);
		}catch (AuthInvalidException $e){
			$this->loger->error($e->getMessage());
			throw new HttpUnauthorizedException($rq, $e->getMessage());
		}
		catch(\Error $e){
			$this->loger->error($e->getMessage());
			throw new HttpUnauthorizedException($rq, "Erreur lors de l'authentification veuillez verifier votre token");
		}


		// avant requête
		//authz middleware
		// $authz = new AuthzMiddleware($user->role);	
		// $rs = $authz->process($rq, $next);



		$rs = $next->handle($rq);
		//après requete
		return $rs;
	}

}
