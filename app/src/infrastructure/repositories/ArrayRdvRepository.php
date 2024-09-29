<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayRdvRepository implements RdvRepositoryInterface
{
    private array $rdvs = [];

    public function __construct() {
            $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
            $r1->setID('r1');
            $r2 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 10:00'));
            $r2->setID('r2');
            $r3 = new RendezVous('p2', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:30'));
            $r3->setID('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }

    public function getRdvById(string $id): RendezVous {
        $rdv = $this->rdvs[$id] ??
            throw new RepositoryEntityNotFoundException("RDV $id not found");

        return $rdv;
    }

    public function getRdvByPraticien(string $id) : array {
        return array_filter($this->rdvs, function($rdv) use($id) {
            return $rdv->praticienID === $id;
        });
    }

    public function addRdv(string $id, RendezVous $rdv): void {
        $this->rdvs[$id] = $rdv;
    }


    public function delete(string $id): void{
        unset($this->rdvs[$id]);
    }


  
}
