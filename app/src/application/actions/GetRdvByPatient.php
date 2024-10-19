<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class getRdvByPatient extends AbstractAction
{
        // todo : check status

    public static function ajouterLiensRdv(array $rdvs, ServerRequestInterface $rq):array{
        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
        return ["rendezVous" => $rdvs,
            "links" => [
                "patient" => $routeParser->urlFor("getPatient", ['id' => $rdvs[0]->patientId])
            ]
        ];
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {

        $status = 200;
        try {
            $rdvs = $this->serviceRdv->getRdvByPatient($args['id']);
            $data = getRdvByPatient::ajouterLiensRdv($rdvs,$rq);
            $rs = JsonRenderer::render($rs, 200, $data);
            $this->loger->info('GetRdvPatient du patient: '.$args['id']);

        } catch (ServiceRDVInvalidDataException $e) {
            $this->loger->error('GetRdvPatient : '.$args['id'].' : '.$e->getMessage());
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (\Exception $e){
            $this->loger->error('GetRdvPatient : '.$args['id'].' : '.$e->getMessage());
            throw new HttpInternalServerErrorException($rq,$e->getMessage());
        }


        return $rs;
    }
}
