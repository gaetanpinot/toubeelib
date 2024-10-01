<?php

namespace toubeelib\application\actions;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;

abstract class AbstractAction
{
    protected ServiceRDVInterface $serviceRdv;
   protected ServicePraticienInterface $servicePraticien; 
    protected string $formatDate;
    /**
     * @param ServiceRDVInterface $srdv
     * @param ServicePraticienInterface $sprt
     * @param string $formatDate
     */
    public function __construct(ServiceRDVInterface $srdv, ServicePraticienInterface $sprt, string $frmDate)
    {
        $this->serviceRdv = $srdv;
        $this->servicePraticien = $sprt;
        $this->formatDate = $frmDate;
    }

    /**
     * @param array<int,mixed> $args
     */
    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;
    

}
