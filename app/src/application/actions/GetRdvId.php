<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;

class GetRdvId extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $rdvs = [
            "rendez_vous" => [
                "id" => "b2dd56c2-700b-3b49-915d-d3be96f7f6af",
                "id_patient" => "461ba172-e0d0-4d91-82eb-60e3941f7492",
                "id_praticien" => "29eb5172-f5aa-3b49-12d4-7050921ab806",
                "spécialité_praticien" => "médecine générale",
                "lieu" => "Nancy",
                "horaire" => "2024-09-02 16:00:00",
                "type" => "présentiel"
            ],
            "links" => [
                "self" => [
                    "href" => "/rdvs/b2dd56c2-700b-3b49-915d-d3be96f7f6af/"
                ],
                "modifier" => [
                    "href" => "/rdvs/b2dd56c2-700b-3b49-915d-d3be96f7f6af/"
                ],
                "annuler" => [
                    "href" => "/rdvs/b2dd56c2-700b-3b49-915d-d3be96f7f6af/"
                ],
                "praticien" => [
                    "href" => "/praticiens/29eb5172-f5aa-3b49-12d4-7050921ab806"
                ],
                "patient" => [
                    "href" => "/patients/461ba172-e0d0-4d91-82eb-60e3941f7492"
                ]
            ]
        ];

        $data = compact('rdvs');
//            $dataAfterFiltering=['service'=>[]];
//            foreach($data['service'] as $d){
//                $d['links']=['detail'=>"/api/services/{$d['id']}"];
//                $dataAfterFiltering['service'][]=$d;
//            }
        $jsonData = json_encode(['type' => 'resource', 'data' => $data]);
        $rs->getBody()->write($jsonData);
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);

        // TODO: Implement __invoke() method.
    }
}