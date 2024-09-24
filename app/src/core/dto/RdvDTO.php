<?php

namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\DTO;

class RdvDTO extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $dateHeure;
    protected PraticienDTO $praticien;
    protected string $specialiteLabel; 
    protected string $patientID; 
    //protected string $consultationType;
    protected int $status;


    /**
     * protected string $id;
    * protected \DateTimeImmutable $dateHeure;
    * protected string $praticienID;
    * protected string $specialite = null; // version simplifiée : une seule spécialité
    * protected string $patientID; 
     *protected int $status;
     */

    public function __construct(RendezVous $r, PraticienDTO $praticienDTO)
    {
        $this->id = $r->getID();
        $this->dateHeure = $r->dateHeure;
        $this->praticien = $praticienDTO;
        $this->specialiteLabel = $r->specialite;
        $this->patientID = $r->patientID;
        //$this->consultationType = $r->consultationType;
        $this->status = $r->status;
    }


}