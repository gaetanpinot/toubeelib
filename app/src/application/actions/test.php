<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class test extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $status = 200;
        try {
            $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());
            $rdvs = $serviceRdv->getRDVById('r1');
            $rs=JsonRenderer::render($rs,200,$rdvs);

        } catch (ServiceRDVInvalidDataException $s) {
            $data = ["erreur" => "Erreur RDV invalide"];
            $status = 404;
        }
        return $rs;

        // TODO: Implement __invoke() method.       // TODO: Implement __invoke() method.
    }
}