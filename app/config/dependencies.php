<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\infrastructure\repositories\PgPraticienRepository;
use toubeelib\infrastructure\repositories\PgRdvRepository;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\create(PgPraticienRepository::class)->constructor(DI\get('pdo.commun')),
    RdvRepositoryInterface::class => DI\create(PgRdvRepository::class)->constructor(DI\get('pdo.commun')),
    // PraticienRepositoryInterface::class => DI\create(ArrayPraticienRepository::class)->constructor(),
    // RdvRepositoryInterface::class => DI\create(ArrayRdvRepository::class)->constructor(),

    //Implementation de repository interface
    // postgres
    // PgPraticienRepository::class=>DI\create(PgPraticienRepository::class)->constructor(DI\get('pdo.commun')),
    // PgRdvRepository::class=>DI\create(PgRdvRepository::class)->constructor(DI\get('pdo.commun')),

    //Services
    ServicePraticienInterface::class => DI\create(ServicePraticien::class)->constructor(DI\get(PraticienRepositoryInterface::class)),
    ServiceRDVInterface::class => DI\create(ServiceRDV::class)->constructor(DI\get(ServicePraticienInterface::class),
        DI\get(RdvRepositoryInterface::class)),

    //PDO
    'pdo.commun' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },


    StreamHandler::class => DI\create(StreamHandler::class)->constructor(DI\get('logs.dir')),
    
    Logger::class => DI\create(Logger::class)->constructor('Toubeelib_logger', [DI\get(StreamHandler::class)])


];
// $co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
