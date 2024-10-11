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


return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    // PraticienRepositoryInterface::class => DI\create(ArrayPraticienRepository::class)->constructor(),
    // RdvRepositoryInterface::class => DI\create(ArrayRdvRepository::class)->constructor(),

    //Implementation de repository interface
    // postgres
    // PgPraticienRepository::class=>DI\create(PgPraticienRepository::class)->constructor(DI\get('pdo.commun')),
    // PgRdvRepository::class=>DI\create(PgRdvRepository::class)->constructor(DI\get('pdo.commun')),

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),

    //PDO
    'pdo.commun' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },
    



];
// $co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
