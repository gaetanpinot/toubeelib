<?php

namespace toubeelib\application\actions;

use _PHPStan_9815bbba4\Nette\Neon\Exception;
use DateTimeImmutable;
use MongoDB\Driver\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;
use Slim\Routing\RouteParser;
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
        $jsonRdv = $rq->getParsedBody();

        $status = 200;
        $champs = ['praticienId', 'patientId', 'specialite', 'dateHeure'];

        $rdvInputValidator = Validator::key('praticienId', Validator::stringType()->notEmpty())
            ->key('patientId', Validator::stringType()->notEmpty())
            ->key('specialite', Validator::stringType()->notEmpty())
            ->key('dateHeure', Validator::dateTime("Y-m-d H:i")->notEmpty());

        try {
            $rdvInputValidator->assert($jsonRdv);
        } catch (NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
        try {
            $dateHeure = DateTimeImmutable::createFromFormat('Y-m-d H:i', $jsonRdv["dateHeure"]);
            $dtoRendezVousCree = $serviceRdv->creerRendezvous($jsonRdv['praticienId'], $jsonRdv['patientId'], $jsonRdv['specialite'], $dateHeure);

            $data = ['rendez_vous' => ['id' => $dtoRendezVousCree->id]];

            $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
            $rs = $rs->withAddedHeader("Location", $routeParser->urlFor("getRdvId", ["id" => $dtoRendezVousCree->id]));

            // TODO renvoyer dto to json
            $status = 201;
        } catch (ServiceRDVInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
//            throw new HttpInternalServerErrorException($rq, "Erreur serveur");
        }
        $rs->getBody()->write(json_encode($data));
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);

        //  creerRendezvous(string $id, string $praticienID, string $patientID, string $specialite, \DateTimeImmutable $dateHeure) : RdvDTO {

    }
}