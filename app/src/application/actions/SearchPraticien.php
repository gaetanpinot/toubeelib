<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\dto\PraticienDTO;

class SearchPraticien extends AbstractAction{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface{
        $jsonPraticien = $rq->getParsedBody();

        $validatorPraticien = Validator::key('tel', Validator::stringType())
            ->key('prenom', Validator::stringType())
            ->key('nom', Validator::stringType())
            ->key('adresse', Validator::stringType())
            ->key('specialite', Validator::stringType());

        try{
            $validatorPraticien->assert($jsonPraticien);

            $pra = new Praticien($jsonPraticien['nom'],$jsonPraticien['prenom'], $jsonPraticien['adresse'], "");
            $pra->setId("");
            $pra->setSpecialite(new Specialite("",$jsonPraticien['specialite']));
            $praticienDto = new PraticienDTO($pra);
            
            $praticiensCherche = $this->servicePraticien->searchPraticien($praticienDto);
            $this->loger->info("recherche du praticien $praticienDto");
            return JsonRenderer::render($rs, 200, $praticiensCherche);

        }catch(NestedValidationException $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }catch(\Exception $e){
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }

}
