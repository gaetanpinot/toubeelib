<?php
namespace toubeelib\application\actions;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Rules\Json;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;


class GetDisposPraticien extends AbstractAction{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data =$rq->getParsedBody();
        $praticienValidator=Validator::key('id',Validator::stringType()->notEmpty());

        try{
            $praticienValidator->assert($data);
            $serviceRdv = new ServiceRDV(new ServicePraticien(new ArrayPraticienRepository()), new ArrayRdvRepository());
            $dispos=$serviceRdv->getListeDisponibilite($data['id']);
            for($i=0; $i<count($dispos);$i++){
                $dispos[$i]=$dispos[$i]->format('Y-m-d H:i:s');
            }
            return JsonRenderer::render($rs, 200, $dispos);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }

    }

}