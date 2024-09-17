<?php
namespace toubeelib\core\domain\entities\patient;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\PatientDTO;

class Patient extends Entity
{
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $numSecu;
    protected string $dateNaissance;
    protected string $mail;
    protected string $traitant;

    public function __construct(string $nom, string $prenom, string $adresse, string $tel, string $dateNaissance, string $numSecu,string $mail,string $traitant)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->dateNaissance = $dateNaissance;
        $this->mail = $mail;
        $this->numSecu= $numSecu;
        $this->traitant=$traitant;
    }



    public function toDTO(): PatientDTO
    {
        return new PatientDTO($this);
    }
}
