<?php

namespace toubeelib\core\domain\entities\rdv;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\dto\PraticienDTO;
use toubeelib\core\domain\entities\praticien;
use toubeelib\core\domain\entities\praticien\specialite;
use toubeelib\core\domain\entities\patient;

class RendezVous extends Entity
{
    protected string $id;
    protected \DateTimeImmutable $dateHeure;
    protected string $praticienID;
    protected string $specialite;
    protected string $patientID;

    protected int $status;
    
    public function getPracticienId(): string{
        return $this->praticienID;
    }

    /**
     * $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
     *       $r1->setID('r1');
     */
    public function __construct(string $praticienID, $patientID, string $specialite, \DateTimeImmutable $dateHeure)
    {
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->dateHeure = $dateHeure;
        $this->specialite = $specialite;
        $this->status = 0;
    }

    public function toDTO(PraticienDTO $praticienDTO): RdvDTO
    {
        return new RdvDTO( $this, $praticienDTO);
    }
}