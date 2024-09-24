<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\domain\entities\rdv\RendezVous\RendezVous;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDVInterface;

class ServiceRDV implements ServiceRDVInterface
    
{
    private RDVRepositoryInterface $rdvRepository;
    private ServicePraticien $servicePraticien;

    public function __construct(ServicePraticien $servicePraticien, RdvRepositoryInterface $rdvRepository) {
        $this->rdvRepository = $rdvRepository;
        $this->servicePraticien = $servicePraticien;
    }
    public function getRDVById(string $id): RdvDTO {
            $rdv = $this->rdvRepository->getRDVById($id);
            $praticien = $this->servicePraticien->getPraticienById($rdv->getPracticienId());
            return $rdv->toDTO($praticien);
    }
}