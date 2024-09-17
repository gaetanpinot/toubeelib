<?php
namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\DTO;

class PatientDTO extends DTO
{
    protected string $ID;
    protected string $numSecu;
    protected string $dateNaissance;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $mail;
    protected string $traitant;

    public function __construct(Praticien $p)
    {
        $this->ID = $p->getID();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        $this->numSecu = $p->numSecu;
        $this->dateNaissance = $p->dateNaissance;
        $this->traitant = $p->traitant;
    }


}
