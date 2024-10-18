<?php
namespace toubeelib\application\actions;


use DateTime;
use DateTimeImmutable;
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


        $jsonDates = $rq->getParsedBody();

        $status = 200;
        $champs = ['id', 'start_Date', 'end_Date'];

        $praticienIdValidator = Validator::key('id',Validator::Uuid()->notEmpty());
        $praticienValidator = Validator::key('start_date', Validator::dateTime($this->formatDate)->notEmpty())
        ->key('end_date', Validator::dateTime($this->formatDate)->notEmpty());


        
        try{

            $praticienValidator->assert($jsonDates);
            $praticienIdValidator->assert($args);
            $dateDebut = DateTimeImmutable::createFromFormat($this->formatDate,$jsonDates['start_date']);
            $dateFin = DateTimeImmutable::createFromFormat($this->formatDate,$jsonDates['end_date']);
            if($dateFin->getTimestamp()<= $dateDebut->getTimestamp()){
                throw new HttpBadRequestException($rq, "Date fin plus petite que Date debut");
            }
            

            $dispos=$this->serviceRdv->getListeDisponibiliteDate($args['id'], $jsonDates['start_date'], $jsonDates['end_date'],);
            for($i=0; $i<count($dispos);$i++){
                $dispos[$i]=$dispos[$i]->format($this->formatDate);
            }
            return JsonRenderer::render($rs, 200, $dispos);
            

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq,$e->getMessage());
        }
    }
}
