<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\RdvDTO;

interface ServiceRDVInterface
{

    public function getRDVById(string $id): RdvDTO;


}