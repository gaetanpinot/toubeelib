<?php

namespace toubeelib\core\dto;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;

class InputRdvDto extends DTO
{
    protected string $praticienId, $specialite, $patientId;
    protected \DateTimeImmutable $dateHeure;

    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getDateHeure(): \DateTimeImmutable
    {
        return $this->dateHeure;
    }

    /**
     * @param string $praticienId
     * @param string $specialite
     * @param string $patientId
     * @param \DateTimeImmutable $dateHeure
     */
    public function __construct(string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure)
    {
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->specialite = $specialite;
        $this->dateHeure = $dateHeure;
    }


}