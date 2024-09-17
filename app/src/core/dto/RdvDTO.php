<?php

namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\rdv\Rdv;
use toubeelib\core\dto\DTO;

class RdvDTO extends DTO
{
  protected string $patient_id;
  protected string $date;
  protected string $praticien_id;
  protected string $valid;  
  protected string $notif;

    public function __construct(Rdv $p)
    {
      $this->patient_id = $patient_id;
      $this->date = $date;
      $this->praticien_id = $praticien_id;
      $this->valid = $valid;
      $this->notif = $notif;
    }


}