<?php
namespace toubeelib\application\actions;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
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
        
        $praticienValidator=Validator::key('id',Validator::stringType()->notEmpty());

        try{
            $praticienValidator->assert($args);
            $dispos=$this->serviceRdv->getListeDisponibilite($args['id']);
            for($i=0; $i<count($dispos);$i++){
                $dispos[$i]=$dispos[$i]->format($this->formatDate);
            }
            return JsonRenderer::render($rs, 200, $dispos);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }

    }

}
