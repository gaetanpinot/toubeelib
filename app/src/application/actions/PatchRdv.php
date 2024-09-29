<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\core\services\rdv\ServiceRDV;

class PatchRdv extends AbstractAction
{

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
                $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());

        $data=$rq->getParsedBody();

        //validation de l'existance de id, idpraticien, specialite, dateHeure
        $rdvInputValidator = Validator::key('id',Validator::stringType()->notEmpty())
            ->key('praticienId', Validator::stringType()->notEmpty())
            ->key('patientId', Validator::stringType()->notEmpty())
            ->key('specialite', Validator::stringType()->notEmpty())
            ->key('dateHeure', Validator::dateTime("Y-m-d H:i")->notEmpty())
            ;

        try{
            $rdvInputValidator->assert($data);
            $inputRdv = InputRdvDto::fromArray($data);
            $inputRdv->setId($data['id']);
            $dto=$serviceRdv->modifRendezVous($inputRdv);
            $data=GetRdvId::ajouterLiensRdv($dto, $rq);
            return JsonRenderer::render($rs, 201, $data);


        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }catch(ServiceRDVInvalidDataException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }



        return $rs;
    }
}
