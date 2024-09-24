<?php

namespace toubeelib\application\actions;

use DateTimeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class GetRdvId extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq , ResponseInterface $rs , array $args): ResponseInterface
    {

        $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()) , new ArrayRdvRepository());
        $rdvs = $serviceRdv->getRDVById($args['id']);
//        $data = $rdvs->toJSON();

        $data =
            [
                "rendez_vous" => [
                    "id" => $rdvs->id ,
                    "id_patient" => $rdvs->patientId ,
                    "id_praticien" => $rdvs->praticien->id ,
                    "spécialité_praticien" => $rdvs->praticien->specialite_label ,
                    "lieu" => $rdvs->praticien->adresse ,
                    "horaire" => $rdvs->dateHeure->format("Y-m-d H:i:s") ,
                    "type" => "inconnu"
                ] ,
                "links" => [
                    "self" => [
                        "href" => "/rdvs/$rdvs->id/"
                    ] ,
                    "modifier" => [
                        "href" => "/rdvs/$rdvs->id/modifier/"
                    ] ,
                    "annuler" => [
                        "href" => "/rdvs/$rdvs->id/annuler/"
                    ] ,
                    "praticien" => [
                        "href" => "/praticiens/{$rdvs->praticien->id}"
                    ] ,
                    "patient" => [
                        "href" => "/patients/$rdvs->patientId"
                    ]
                ]
            ];
        $rs->getBody()->write(json_encode($data));
        return $rs
            ->withHeader('Content-Type' , 'application/json')
            ->withStatus(200);

        // TODO: Implement __invoke() method.
    }
}