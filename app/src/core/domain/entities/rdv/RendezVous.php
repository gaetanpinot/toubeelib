<?php

namespace toubeelib\core\domain\entities\rdv;

use Monolog\DateTimeImmutable;
use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\dto\PraticienDTO;

class RendezVous extends Entity
{
    //todo :  0 maintenu(default) / 1 honoré / 2 non honoré / 3 annulé /  4 payé / 5 pas payé 
    public static int $MAINTENU = 0;
    public static int $HONORE = 1;
    public static int $NON_HONORE = 2;
    public static int $ANNULE = 3;
    public static int $PAIE = 4;
    public static int $PAS_PAYE = 5;

    protected DateTimeImmutable $dateHeure;
    protected string $praticienID;
    protected string $specialite;
    protected string $patientID;

    protected int $status;


    public function getDateHeure(): DateTimeImmutable
    {
        return $this->dateHeure;
    }

    public function getPraticienID(): string
    {
        return $this->praticienID;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function getPatientID(): string
    {
        return $this->patientID;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
    public function getPracticienId(): string
    {
        return $this->praticienID;
    }

    /**
     * $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
     *       $r1->setID('r1');
     */
    public function __construct(string $praticienID, string $patientID, string $specialite, DateTimeImmutable $dateHeure)
    {
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->dateHeure = $dateHeure;
        $this->specialite = $specialite;
        $this->status = RendezVous::$MAINTENU;
    }

    public static function fromInputDto(InputRdvDto $rdv):RendezVous
    {
        return new RendezVous(
            $rdv->getPraticienId(),
            $rdv->getPatientId(),
            $rdv->getSpecialite(),
            $rdv->getDateHeure());
    }

    public function toDTO(PraticienDTO $praticienDTO): RdvDTO
    {
        return new RdvDTO($this, $praticienDTO);
    }
}
