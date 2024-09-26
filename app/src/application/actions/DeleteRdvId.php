<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class DeleteRdvId extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());
        $status = 200;
        try {
            $serviceRdv->supprimerRendezVous($args['id']);
            $data = [];
        } catch (ServiceRDVInvalidDataException $e) {
            $data = ["erreur" => "Rendez vous invalide"];
            $status = 404;
        }
        $rs->getBody()->write(json_encode($data));
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
        // TODO: Implement __invoke() method.
    }
}