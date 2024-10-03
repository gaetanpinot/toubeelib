<?php
namespace toubeelib\infrastructure\repositories;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use PDO;

class PgRdvRepository implements  RdvRepositoryInterface{

    protected PDO $pdo;

    public function __construct(PDO $pdo){
    $this->pdo=$pdo;
}

    public function getRdvById(string $id): RendezVous
    {
    }

    public function addRdv(string $id, RendezVous $rdv): void
    {
    }

    public function delete(string $id): void
    {
    }

    public function getRdvByPraticien(string $id): array
    {
    }

}
