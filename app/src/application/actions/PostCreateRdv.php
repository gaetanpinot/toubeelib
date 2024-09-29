<?php

namespace toubeelib\application\actions;

use Error;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

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
            //validation
            $rdvInputValidator->assert($jsonRdv);
            //formatage
            $inputRdvDto = new InputRdvDto($jsonRdv['praticienId'], $jsonRdv['patientId'], $jsonRdv['specialite'], $jsonRdv['dateHeure']);
            $dtoRendezVousCree = $serviceRdv->creerRendezvous($inputRdvDto);


            // route parser
            $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
            $rs = JsonRenderer::render($rs, 201, GetRdvId::ajouterLiensRdv($dtoRendezVousCree,$rq));
            // entrée dans le header avec le nom Location et pour valeur la route vers le rdv crée
            $rs = $rs->withAddedHeader("Location", $routeParser->urlFor("getRdv", ["id" => $dtoRendezVousCree->id]));

            return $rs;
        } catch (NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (ServiceRDVInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
//            throw new HttpInternalServerErrorException($rq, "Erreur serveur");
        } catch (Error $e) {
            echo $e->getTraceAsString();
        }


    }
}