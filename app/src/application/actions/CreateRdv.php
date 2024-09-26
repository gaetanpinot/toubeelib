<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class CreateRDV extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());
        $rdvs = $serviceRdv->creerRendezvous('r5', 'p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-27 09:00'));
        

        $data = $rdvs->toJSON();
        $rs->getBody()->write($data);
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}