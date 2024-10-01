<?php

use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;


return [

    PraticienRepositoryInterface::class => DI\create(ArrayPraticienRepository::class)->constructor(),
    RdvRepositoryInterface::class => DI\create(ArrayRdvRepository::class)->constructor(),
    ServicePraticienInterface::class => DI\create(ServicePraticien::class)->constructor(DI\get(PraticienRepositoryInterface::class)),
    ServiceRDVInterface::class => DI\create(ServiceRDV::class)->constructor(DI\get(ServicePraticienInterface::class),
        DI\get(RdvRepositoryInterface::class))

];
