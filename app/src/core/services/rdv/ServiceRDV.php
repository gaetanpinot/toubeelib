<?php

namespace toubeelib\core\services\rdv;

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

    /*string $praticienID, $patientID, string $specialite, \DateTimeImmutable $dateHeure*/
    public function creerRendezvous(string $praticienID, string $patientID, string $specialite, \DateTimeImmutable $dateHeure) : RdvDTO {
        $rdv = new RendezVous($praticienID, $patientID, $specialite, $dateHeure);
        $this->rdvRepository->save($rdv);
    }
}