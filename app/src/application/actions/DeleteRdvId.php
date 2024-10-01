<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class DeleteRdvId extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $status = 200;
        try {
            $this->serviceRdv->annulerRendezVous($args['id']);
            $data = [];
        } catch (ServiceRDVInvalidDataException $e) {
            throw new HttpNotFoundException($rq,$e->getMessage());
        }
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
        // TODO: Implement __invoke() method.
    }
}
