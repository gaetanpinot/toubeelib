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

//    private DateInterval $interval = DateInterval::createFromDateString('15 minutes');

    public function __construct(ServicePraticien $servicePraticien, RdvRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
        $this->servicePraticien = $servicePraticien;
    }

    public function getRDVById(string $id): RdvDTO
    {
        try {
            $rdv = $this->rdvRepository->getRDVById($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException('invalid RDV ID');
        }
        $praticien = $this->servicePraticien->getPraticienById($rdv->getPracticienId());
        return $rdv->toDTO($praticien);
    }

    /*string $praticienID, $patientID, string $specialite, \DateTimeImmutable $dateHeure*/
    public function creerRendezvous(string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure): RdvDTO
    {
        $rdv = new RendezVous($praticienId, $patientId, $specialite, $dateHeure);

        // ! temporaire a remplacer par uuid
        $id = 'r' . random_int(0, 1000000000);
        $rdv->setID($id);
        // ! temporaire

        try {
            $praticien = $this->servicePraticien->getPraticienById($praticienId);
            if ($praticien->specialite_label != $this->servicePraticien->getSpecialiteById($specialite)->label) {
                throw new \Exception($praticien->specialite_label . "=!" . $specialite);
            }
            /*if (!in_array($this->getListeDisponibilite($praticienID),$dateHeure)) {
                throw new \Exception("Praticien indisponible"); 
            }*/
        } catch (\Exception $e) {
            throw new ServiceRDVInvalidDataException("Création de rdv impossible : " . $e->getMessage());
        }
        $this->rdvRepository->addRDV($id, $rdv);
        return $rdv->toDTO($praticien);
    }

    public function getListeDisponibilite(string $id): array
    {

        return ([]);
    }

    /*string $praticienID*/
    public function supprimerRendezVous(string $id): void
    {
        try {
            $rdv = $this->rdvRepository->getRDVById($id);
            $this->rdvRepository->delete($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException('invalid RDV ID');
        }
    }
}