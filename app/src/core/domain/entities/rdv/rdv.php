<?php
namespace toubeelib\core\domain\entities\rdv;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\RdvDTO;

class rdv extends Entity
{

    protected string $patient_id;
    protected string $date;
    protected string $praticien_id;
    protected string $valid;  
    protected string $notif;

    public function __construct(string $patient_id, string $date, string $praticien_id, string $valid, string $notif)
    {
        $this->patient_id = $patient_id;
        $this->date = $date;
        $this->praticien_id = $praticien_id;
        $this->valid = $valid;
        $this->notif = $notif;
    }



    public function getDTO(): PatientDTO
    {
        return PatientDTO($this);
    }
    public function getDTO(): PraticienDTO
    {
        return PraticienDTO($this);
    }
}
