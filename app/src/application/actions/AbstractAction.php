<?php

namespace toubeelib\application\actions;


use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;

abstract class AbstractAction
{
    protected ServiceRDVInterface $serviceRdv;
   protected ServicePraticienInterface $servicePraticien; 
    protected string $formatDate;
    protected Container $cont;

    protected Logger $loger;
    /**
     * @param ServiceRDVInterface $srdv
     * @param ServicePraticienInterface $sprt
     * @param string $formatDate
     */
    public function __construct(Container $cont)
    {
        $this->serviceRdv = $cont->get(ServiceRDVInterface::class);
        $this->servicePraticien = $cont->get(ServicePraticienInterface::class);
        $this->formatDate = $cont->get('date.format');
$this->loger = $cont->get(Logger::class);
    }

    /**
     * @param array<int,mixed> $args
     */
    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;
    

}
