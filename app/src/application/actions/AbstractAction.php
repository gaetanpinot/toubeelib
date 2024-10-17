<?php

namespace toubeelib\application\actions;


use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\ServiceAuthInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\providers\auth\AuthnProviderInterface;

abstract class AbstractAction
{
    protected ServiceRDVInterface $serviceRdv;
    protected ServicePraticienInterface $servicePraticien; 
    protected AuthnProviderInterface $authProvider;
    protected string $formatDate;
    protected Container $cont;
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
    }

    /**
     * @param array<int,mixed> $args
     */
    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface ;
    

}
