<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\dto\RdvDTO;

interface ServiceRDVInterface
{

    public function getRdvById(string $id): RdvDTO;
public function creerRendezvous(InputRdvDto $rdv): RdvDTO;
public function modifRendezVous(InputRdvDto $rdv): RdvDTO;
public function annulerRendezVous(string $id): void;
//public function supprimerRendezVous(string $id): void;
}
