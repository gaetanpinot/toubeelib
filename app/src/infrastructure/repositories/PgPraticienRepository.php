<?php
namespace toubeelib\infrastructure\repositories;

use DI\Container;
use toubeelib\core\repositoryInterfaces\RepositoryInternalException;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use PDO;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PgPraticienRepository implements PraticienRepositoryInterface{

    protected PDO $pdo;

    public function __construct(Container $cont){
        $this->pdo=$cont->get('pdo.commun');
    }
    public function getSpecialiteById(string $id): Specialite
    {
        try{
            $query = 'select * from specialite where id = :id;';
            $speSelect=$this->pdo->prepare($query);
            $speSelect->execute(['id'=> $id]);
            $result=$speSelect->fetch();

            if($result){
                return new Specialite($result['id'], $result['label'], $result['description']);
            }else{
                throw new RepositoryEntityNotFoundException("Specialite $id non trouvé");
            }

        }catch(\PDOException $e){
            throw new RepositoryInternalException("erreur");
        }
    }

    public function save(Praticien $praticien): string
    {
    }

    public function getPraticienById(string $id): Praticien
    {
        try{
            $query='select praticien.id as id, praticien.nom as nom, praticien.prenom as prenom, praticien.adresse as adresse, praticien.tel as tel,
            specialite.id as speid, specialite.label as spelabel, specialite.description as spedes from praticien,specialite where specialite.id=praticien.specialite and praticien.id=:id;';
            $praticienSelect=$this->pdo->prepare($query);
            $praticienSelect->execute(['id' => $id]);
            $result = $praticienSelect->fetch();
            if($result){
                $retour= new Praticien($result['nom'],$result['prenom'],$result['adresse'],$result['tel']);
                $retour->setId($result['id']);
                $retour->setSpecialite(new Specialite($result['speid'],$result['spelabel'],$result['spedes']));
                return $retour;
            }else{
                throw new RepositoryEntityNotFoundException("Praticien $id non trouvé");
            }

        }catch(\PDOException $e){
            throw new RepositoryInternalException('Erreure bd');
        }
    }
}
