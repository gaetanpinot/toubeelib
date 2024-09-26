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

    const INTERVAL =  30; 
    const HDEBUT = [9,00];
    const HFIN = [17,30];

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
            if ($praticien->specialiteLabel != $this->servicePraticien->getSpecialiteById($specialite)->label) {
                throw new \Exception($praticien->specialiteLabel . "=!" . $specialite);
            }
            
            if (!in_array($dateHeure, $this->getListeDisponibilite($praticienId))) {
                throw new \Exception("Praticien indisponible"); 
            }
        } catch(\Exception $e) {
            throw new \Exception("Création de rdv impossible : " .$e->getMessage());
        }
        $this->rdvRepository->addRDV($id, $rdv);
        return $rdv->toDTO($praticien);
    }

    public function getListeDisponibilite(string $idPraticien) : array {
        $results = [];
        $listeRDV = $this->rdvRepository->getRDVByPraticien($idPraticien);
        $listeRDVHorraires = array_map( function($rdv) {
            return $rdv->dateHeure->format('Y-m-d H:i');
        }, $listeRDV);
        $startDate = new \DateTimeImmutable("now");
        $startDate = $startDate->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);
        $endDate = $startDate->add(new DateInterval("P7D"))->setTime(ServiceRDV::HFIN[0], ServiceRDV::HFIN[1]);

        while($startDate->diff($endDate)->format('%d') > 0) {
            while ($startDate->format('U')%86400 <= ServiceRDV::HFIN[0]*3600+ServiceRDV::HFIN[1]*60) {
                
                if (!in_array($startDate->format('Y-m-d H:i'), $listeRDVHorraires)) {
                    
                    $results[] = $startDate;
                }
                $startDate = $startDate->add(new DateInterval("PT30M"));
            }
            $startDate = $startDate->add(new DateInterval('P1D'))->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);
        }
        
    return $results;
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