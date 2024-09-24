<?php

namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\PraticienDTO;
use toubeelib\core\dto\DTO;

class RdvDTO extends DTO
{
    public string $id;
    public \DateTimeImmutable $dateHeure;
    public PraticienDTO $praticien;
    public string $specialiteLabel;
    public string $patientId;
    public string $consultationType;
    public int $status;


    public function __construct(RendezVous $r, PraticienDTO $praticienDTO)
    {
        $this->id = $r->getID();
        $this->dateHeure = $r->dateHeure;
        $this->praticien = $praticienDTO;
        $this->specialiteLabel = $r->specialite;
        $this->patientId = $r->patientID;
//        $this->consultationType = $r->consultationType;
        $this->status = $r->status;
    }


}