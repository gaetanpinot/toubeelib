<?php

namespace toubeelib\core\dto;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;

class InputRdvDto extends DTO
{
    protected string $praticienId, $specialite, $patientId;
    protected \DateTimeImmutable $dateHeure;

    /**
     * @param string $praticienId
     * @param string $specialite
     * @param string $patientId
     * @param \DateTimeImmutable $dateHeure
     */
    public function __construct(string $praticienId, string $specialite, string $patientId, \DateTimeImmutable $dateHeure)
    {
        $this->praticienId = $praticienId;
        $this->specialite = $specialite;
        $this->patientId = $patientId;
        $this->dateHeure = $dateHeure;
    }


}