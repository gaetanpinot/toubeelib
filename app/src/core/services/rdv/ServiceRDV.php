<?php

namespace toubeelib\core\services\rdv;

use DateInterval;
use DateTimeImmutable;
use Error;
use Faker\Core\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\ServiceOperationInvalideException;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use MonologLogger;
use MonologHandlerStreamHandler;

class ServiceRDV implements ServiceRDVInterface

{
    private RdvRepositoryInterface $rdvRepository;
    private ServicePraticien $servicePraticien;

    public const INTERVAL = 30;
    public const HDEBUT = [9, 00];
    public const HFIN = [17, 30];

    public function __construct(ServicePraticien $servicePraticien, RdvRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
        $this->servicePraticien = $servicePraticien;
    }

    public function getRdvById(string $id): RdvDTO
    {
        try {
            $rdv = $this->rdvRepository->getRdvById($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException('invalid RDV ID');
        }
        $praticien = $this->servicePraticien->getPraticienById($rdv->getPracticienId());
        return $rdv->toDTO($praticien);

    }

    /*string $praticienID, $patientID, string $specialite, \DateTimeImmutable $dateHeure*/
    public function creerRendezvous(InputRdvDto $inputRdvDto): RdvDTO
    {
        $rdv = RendezVous::fromInputDto($inputRdvDto);

        $id = RamseyUuid::uuid4()->__toString();
        $rdv->setId($id);

        try {
            $praticien = $this->servicePraticien->getPraticienById($rdv->getPraticienID());
            if ($praticien->specialiteLabel != $this->servicePraticien->getSpecialiteById($rdv->getSpecialite())->label) {
                throw new \Exception($praticien->specialiteLabel . "=!" . $rdv->getSpecialite());
            }

            if (!in_array($rdv->getDateHeure(), $this->getListeDisponibilite($rdv->getPraticienID()))) {
                throw new \Exception("Praticien indisponible");
            }
        } catch (\Exception $e) {
            throw new ServiceRDVInvalidDataException("Création de rdv impossible : " . $e->getMessage());
        }
        $this->rdvRepository->addRdv($id, $rdv);
        return $rdv->toDTO($praticien);
    }

    // TODO: transferer methode a ServicePraticien
    public function getListeDisponibilite(string $idPraticien): array
    {

        $results = [];
        $listeRDV = $this->rdvRepository->getRdvByPraticien($idPraticien);
        $listeRDVHorraires = array_map(function ($rdv) {
            if ($rdv->status != RendezVous::ANNULE) {
                $rr= $rdv->dateHeure->format('Y-m-d H:i');
                return $rr;

            }
        }, $listeRDV);
        $startDate = new \DateTimeImmutable("now");
        $startDate = $startDate->setTime(ServiceRDV::HDEBUT[0], ServiceRDV::HDEBUT[1]);
        $endDate = $startDate->add(new DateInterval("P7D"))->setTime(ServiceRDV::HFIN[0], ServiceRDV::HFIN[1]);

        while ($startDate->diff($endDate)->format('%d') > 0) {
            while ($startDate->format('U') % 86400 <= ServiceRDV::HFIN[0] * 3600 + ServiceRDV::HFIN[1] * 60) {

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
    public function annulerRendezVous(string $id ): RdvDTO
    {
        try {
            $rdvAAnnuler= $this->getRdvById($id);
            if($rdvAAnnuler->getStatus() == RendezVous::MAINTENU ||
            $rdvAAnnuler->getStatus() == RendezVous::ANNULE){
                $this->rdvRepository->cancelRdv($id);
                $rdvAAnnuler->setStatus(RendezVous::ANNULE);
                return $rdvAAnnuler;
            }else{
                throw new ServiceOperationInvalideException("Rendez vous $id non annulable");
            }
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServiceRDVInvalidDataException($e->getMessage());
        }
    }


    /* string $id, string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure */
    public function modifRendezVous(InputRdvDto $inputRdv) : RdvDTO {
        
        //ancien rdv
        $rdvOld = $this->rdvRepository->getRdvById($inputRdv->getId());

        //praticien du nouveau rdv
        $praticien = $this->servicePraticien->getPraticienById($inputRdv->getPraticienId());

        if ($rdvOld->getDateHeure() != $inputRdv->getDateHeure() || $rdvOld->getPracticienId() != $inputRdv->getPraticienId() ) {
            
            $this->supprimerRendezVous($inputRdv->getId()); 
            $res = $this->creerRendezvous($inputRdv);
            $res->status = $rdvOld->getStatus();
            return $res;
        } else {
            if ($praticien->specialiteLabel != $this->servicePraticien->getSpecialiteById($rdvOld->getSpecialite())->label) {
                throw new \Exception($praticien->specialiteLabel . "=!" . $rdvOld->getSpecialite());
            }
            //$this->rdvRepository->setAttribute($rdvOld->getId(), "specialiteLabel",$inputRdv->getSpecialite());
            //$this->rdvRepository->setAttribute($rdvOld->getId(), "patientId",$inputRdv->getPatientId());
            $rdvOld->patientId = $inputRdv->getPatientId();

            
            
            $rdvOld->specialiteLabel = $inputRdv->getSpecialite();
            return $rdvOld->toDTO($praticien);
        }
    }

}
