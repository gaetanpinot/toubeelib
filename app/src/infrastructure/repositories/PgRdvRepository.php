<?php
namespace toubeelib\infrastructure\repositories;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use PDO;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PgRdvRepository implements  RdvRepositoryInterface{

    protected PDO $pdo;

    public function __construct(PDO $pdo){
    $this->pdo=$pdo;
}

// id UUID,
// date timestamp,
// patientId UUID,
// praticienId UUID,
// status int,
// primary key(id),
    public function getRdvById(string $id): RendezVous
    {
        $query='select * from rdv where id=:id;';
        $statement=$this->pdo->prepare($query);
        $statement->execute(['id'=>$id]);
        $rdv=$statement->fetch();
        if($rdv){
            return new RendezVous($rdv['praticienId'],$rdv['patientId'],'spe',$rdv['date']);
        }else{
            throw new RepositoryEntityNotFoundException("Rendez vous $id n'existe pas");
        }
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
