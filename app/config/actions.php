<?php

use toubeelib\application\actions\DeleteRdvId;
use toubeelib\application\actions\GetRdvId;
use toubeelib\application\actions\PatchRdv;
use toubeelib\application\actions\PostCreateRdv;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\application\actions\GetDisposPraticien;
use toubeelib\core\services\rdv\ServiceRDVInterface;


return [

    GetDisposPraticien::class=>DI\create(GetDisposPraticien::class)->constructor(
        DI\get(ServiceRDVInterface::class),
        DI\get(ServicePraticienInterface::class),
        DI\get('date.format') ),

    GetRdvId::class => DI\create(GetRdvId::class)->constructor(
        DI\get(ServiceRDVInterface::class),
        DI\get(ServicePraticienInterface::class),
        DI\get('date.format') ),
    PatchRdv::class => DI\create(PatchRdv::class)->constructor(
        DI\get(ServiceRDVInterface::class),
        DI\get(ServicePraticienInterface::class),
        DI\get('date.format') ),
    PostCreateRdv::class => DI\create(PostCreateRdv::class)->constructor(
        DI\get(ServiceRDVInterface::class),
        DI\get(ServicePraticienInterface::class),
        DI\get('date.format') ),
    DeleteRdvId::class => DI\create(DeleteRdvId::class)->constructor(
        DI\get(ServiceRDVInterface::class),
        DI\get(ServicePraticienInterface::class),
        DI\get('date.format') ),

];
