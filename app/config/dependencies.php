<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\ServiceAuth;
use toubeelib\core\services\ServiceAuthInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\infrastructure\repositories\PgAuthRepository;
use toubeelib\infrastructure\repositories\PgPraticienRepository;
use toubeelib\infrastructure\repositories\PgRdvRepository;
use toubeelib\providers\auth\AuthnProviderInterface;
use toubeelib\providers\auth\JWTAuthnProvider;
use toubeelib\providers\auth\JWTManager;


return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class=> DI\autowire(PgAuthRepository::class),
    // PraticienRepositoryInterface::class => DI\create(ArrayPraticienRepository::class)->constructor(),
    // RdvRepositoryInterface::class => DI\create(ArrayRdvRepository::class)->constructor(),

    //Implementation de repository interface
    // postgres
    // PgPraticienRepository::class=>DI\create(PgPraticienRepository::class)->constructor(DI\get('pdo.commun')),
    // PgRdvRepository::class=>DI\create(PgRdvRepository::class)->constructor(DI\get('pdo.commun')),

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    ServiceAuthInterface::class => DI\autowire(ServiceAuth::class),

    //PDO
    'pdo.commun' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },
    'pdo.auth' => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('auth.db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },

    //auth
    JWTManager::class=> DI\autowire(JWTManager::class),
    AuthnProviderInterface::class => DI\autowire(JWTAuthnProvider::class),
    



];
// $co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
