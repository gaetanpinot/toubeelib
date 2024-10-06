<?php

namespace toubeelib\core\repositoryInterfaces;

use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\InputRdvDto;

interface RdvRepositoryInterface
{

    public function getRdvById(string $id): RendezVous;
    public function addRdv(string $id, RendezVous $rdv):void;
    public function delete(string $id):void;
    public function cancelRdv(string $id,  ): void;
    public function getRdvByPraticien(string $id):array;
    public function modifierRdv(RendezVous $rdv): void;

}
