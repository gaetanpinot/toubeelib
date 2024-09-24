<?php

namespace toubeelib\core\services\rdv;

use DateInterval;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class ServiceRDV implements ServiceRDVInterface
    
{
    private RDVRepositoryInterface $rdvRepository;
    private ServicePraticien $servicePraticien;

    private DateInterval $interval = DateInterval::createFromDateString('15 minutes');

    public function __construct(ServicePraticien $servicePraticien, RdvRepositoryInterface $rdvRepository) {
        $this->rdvRepository = $rdvRepository;
        $this->servicePraticien = $servicePraticien;
    }
    public function getRDVById(string $id): RdvDTO {
        try {
            $rdv = $this->rdvRepository->getRDVById($id);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException('invalid RDV ID');
        } 
            $praticien = $this->servicePraticien->getPraticienById($rdv->getPracticienId());
            return $rdv->toDTO($praticien);
    }

    /*string $praticienID, string $patientID, string $specialite, \DateTimeImmutable $dateHeure*/
    public function creerRendezvous(string $id, string $praticienID, string $patientID, string $specialite, \DateTimeImmutable $dateHeure) : RdvDTO {
        $rdv = new RendezVous($praticienID, $patientID, $specialite, $dateHeure);
        $rdv->setID($id);
        try {
            $praticien = $this->servicePraticien->getPraticienById($praticienID);
            if ($praticien->specialite_label != $this->servicePraticien->getSpecialiteById($specialite)->label) {
                throw new \Exception($praticien->specialite_label."=!".$specialite);
            }
            /*if (!in_array($this->getListeDisponibilite($praticienID),$dateHeure)) {
                throw new \Exception("Praticien indisponible"); 
            }*/
        } catch(\Exception $e) {
            throw new \Exception("Création de rdv impossible : " .$e->getMessage());
        }
        $this->rdvRepository->addRDV($id, $rdv);
        return $rdv->toDTO($praticien);
    }

    public function getListeDisponibilite(string $id) : array {

    }

    /*string $rdvId*/
    public function supprimerRendezVous(string $id) : RdvDTO {
        try {
            $rdv = $this->rdvRepository->getRDVById($id);
            $this->rdvRepository->delete($rdv);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException('invalid RDV ID');
        } 
    }

    /* string $id, string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure */
    public function ModifRendezVous(string $id, string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure) : RdvDTO {
        try {
            // todo : test rdvId exists
            // todo : test NEW praticienId exists

            $rdv = $this->rdvRepository->getRDVById($id);
            $praticien = $this->servicePraticien->getPraticienById($praticienID);

            $rdvId = $rdv->$id;
    
            // TODO: prendre les infos de l'ancient rdv
            $getSpecialite = $this->$rdvRepository->getSpecialite($specialite);
            $getPatientId = $this->$rdvRepository->getPatientId($patientId);

            
            // todo: test spé avec praticien
            if ($praticien->specialite_label = $getSpecialite) {
                throw new \Exception($praticien->specialite_label."=".$getSpecialite);

                // !: supp ancient rdv

                $this->rdvRepository->delete($rdv);

                // !: créer nouveau rdv avec RdvId et spé qui reste en changeant date, praticien
    
                $rdv = new RendezVous($rdvId, $praticien, $getPatientId, $getspecialite, $dateHeure);
                $this->rdvRepository->addRdv($rdv);
                return $rdv->toDTO($praticien);

            }
    
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException('RDV invalide' . $e->getMessage());
        } 
    }
}