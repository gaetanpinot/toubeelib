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


class GetDisposPraticienDate extends AbstractAction{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        //echo "test action for GetDisposPraticienDate";


        //var_dump($rq->getParsedBody()); 


        $jsonRdv = $rq->getParsedBody();

        $status = 200;
        $champs = ['id', 'test_start_Date', 'test_end_Date'];

        $praticienValidator=Validator::key('praticienId',Validator::stringType()->notEmpty());

        if ($jsonRdv["test_start_Date"] != null) {
            $praticienValidator = Validator::key('test_start_Date', Validator::dateTime($this->formatDate));
        } else {
            $jsonRdv["test_start_Date"] = null;
        }
        
        if ($jsonRdv["test_end_Date"] != null) {
            $praticienValidator = Validator::key('test_end_Date', Validator::dateTime($this->formatDate));
        } else {
            $jsonRdv["test_end_Date"] = null;
        } 

        
        try{

            $praticienValidator->assert($jsonRdv);

            $dispos=$this->serviceRdv->getListeDisponibiliteDate($jsonRdv['praticienId'], $jsonRdv['test_start_Date'], $jsonRdv['test_end_Date'],);
            for($i=0; $i<count($dispos);$i++){
                $dispos[$i]=$dispos[$i]->format($this->formatDate);
            }
            return JsonRenderer::render($rs, 200, $dispos);
            

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }
    }
}
