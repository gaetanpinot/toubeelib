<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\RdvDTO;

interface ServiceRDVInterface
{

    public function getRDVById(string $id): RdvDTO;

}
