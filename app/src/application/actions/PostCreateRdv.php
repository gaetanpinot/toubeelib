<?php

namespace toubeelib\application\actions;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use function DI\string;

class PostCreateRdv extends AbstractAction
{


    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());

        $status = 200;
        $champs = ['praticienId', 'patientId', 'specialite', 'dateHeure'];
        foreach ($champs as $c) {
            if (!isset($_POST[$c])) {
                $status = 400;
                $data = ["erreur" => "donnée $c manquante"];
                break;
            }
        }
        if ($status == 200) {
            try {
                $dateHeure= DateTimeImmutable::createFromFormat('Y-m-d H:i',$_POST["dateHeure"]);
                $dtoRendezVousCree=$serviceRdv->creerRendezvous($_POST['praticienId'], $_POST['patientId'], $_POST['specialite'], $dateHeure);
                $data=['rendez_vous'=>['id'=>$dtoRendezVousCree->id]];
            }catch (ServiceRDVInvalidDataException $e ){
                $status=400;
                $data=['erreur'=>$e->getMessage()];
            }
        }
        $rs->getBody()->write(json_encode($data));
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);

        //  creerRendezvous(string $id, string $praticienID, string $patientID, string $specialite, \DateTimeImmutable $dateHeure) : RdvDTO {

    }
}