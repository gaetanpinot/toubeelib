<?php

namespace toubeelib\application\actions;

use DateTimeInterface;
use PHPUnit\Util\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class GetRdvId extends AbstractAction
{

    public static function ajouterLiensRdv(RdvDTO $rdv, ServerRequestInterface $rq){
        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
        return ["rendezVous" => $rdv,
            "links" => [
                "self" => $routeParser->urlFor("getRdv", ['id' => $rdv->id]),
                "praticien" => $routeParser->urlFor("getPraticien", ['id' => $rdv->praticien->id]),
                "patient" => $routeParser->urlFor("getPatient", ['id' => $rdv->patientId])
            ]
        ];
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $status = 200;
        try {
            $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());
            $rdvs = $serviceRdv->getRDVById($args['id']);

            $data=GetRdvId::ajouterLiensRdv($rdvs,$rq);
            $rs = JsonRenderer::render($rs, 200, $data);


//            $data =
//                [
//                    "rendez_vous" => [
//                        "id" => $rdvs->id,
//                        "id_patient" => $rdvs->patientId,
//                        "id_praticien" => $rdvs->praticien->id,
//                        "spécialité_praticien" => $rdvs->praticien->specialiteLabel,
//                        "lieu" => $rdvs->praticien->adresse,
//                        "horaire" => $rdvs->dateHeure->format("Y-m-d H:i:s"),
//                        "type" => "inconnu"
//                    ],
//                    "links" => [
//                        "self" => [
//                            "href" => "/rdvs/$rdvs->id/"
//                        ],
//                        "praticien" => [
//                            "href" => "/praticiens/{$rdvs->praticien->id}"
//                        ],
//                        "patient" => [
//                            "href" => "/patients/$rdvs->patientId"
//                        ]
//                    ]
//                ];
        } catch (ServiceRDVInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (\Exception $e){
            throw new HttpInternalServerErrorException($rq,$e->getMessage());
        }
//        $rs->getBody()->write(json_encode($data));
//        return $rs
//            ->withHeader('Content-Type', 'application/json')
//            ->withStatus($status);

        return $rs;
    }
}