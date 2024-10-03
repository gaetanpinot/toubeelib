<?php
namespace toubeelib\infrastructure\repositories;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use PDO;

class PgPraticienRepository implements PraticienRepositoryInterface{

    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo=$pdo;
    }
    public function getSpecialiteById(string $id): Specialite
    {
    }

    public function save(Praticien $praticien): string
    {
    }

    public function getPraticienById(string $id): Praticien
    {
    }
}
