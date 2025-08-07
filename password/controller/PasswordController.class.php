<?php
require_once("password/model/PasswordService.class.php");
require_once("password/model/PasswordEntry.class.php");

class PasswordController
{
    private $service;
    private $entry;
    private $userData;

    public function __construct()
    {
        $this->service = new PasswordService();
        $this->entry = new PasswordEntry();
    }

    /**
     * Gestisce l'inserimento di un nuovo servizio
     */
    public function insertService($data)
    {
        if (!empty($data['name'])) {
            return $this->service->insertService(
                trim($data['name']),
                trim($data['description']),
                $_SESSION['iduser']
            );
        }
        return false;
    }

    /**
     * Gestisce l'aggiornamento di un servizio
     */
    public function updateService($data)
    {
        if (!empty($data['id']) && !empty($data['name']) && !empty($data['description'])) {
            return $this->service->updateService(
                $data['id'],
                trim($data['name']),
                trim($data['description']),
                $_SESSION['iduser']
            );
        }
        return false;
    }

    /**
     * Gestisce l'eliminazione di un servizio
     */
    public function deleteService($id)
    {
        if (!empty($id)) {
            return $this->service->deleteService($id, $_SESSION['iduser']);
        }
        return false;
    }

    /**
     * Ottiene tutti i servizi dell'utente
     */
    public function getServices()
    {
        return $this->service->selectServicesByUser($_SESSION['iduser']);
    }

    /**
     * Ottiene un servizio specifico
     */
    public function getServiceById($id)
    {
        return $this->service->selectServiceById($id, $_SESSION['iduser']);
    }

    /**
     * Gestisce l'inserimento di una nuova password
     */
    public function insertPassword($data)
    {
        if (!empty($data['service_id']) && !empty($data['username']) && !empty($data['password'])) {
            return $this->entry->insertPassword(
                $data['service_id'],
                trim($data['username']),
                !empty($data['email']) ? trim($data['email']) : null,
                trim($data['password']),
                !empty($data['notes']) ? trim($data['notes']) : null,
                $_SESSION['iduser']
            );
        }
        return false;
    }

    /**
     * Gestisce l'aggiornamento di una password
     */
    public function updatePassword($data)
    {
        if (!empty($data['id']) && !empty($data['service_id']) && !empty($data['username']) && !empty($data['password'])) {
            return $this->entry->updatePassword(
                $data['id'],
                $data['service_id'],
                trim($data['username']),
                !empty($data['email']) ? trim($data['email']) : null,
                trim($data['password']),
                !empty($data['notes']) ? trim($data['notes']) : null,
                $_SESSION['iduser']
            );
        }
        return false;
    }

    /**
     * Gestisce l'eliminazione di una password
     */
    public function deletePassword($id)
    {
        if (!empty($id)) {
            return $this->entry->deletePassword($id, $_SESSION['iduser']);
        }
        return false;
    }

    /**
     * Ottiene tutte le password dell'utente
     */
    public function getPasswords()
    {
        return $this->entry->selectPasswordsByUser($_SESSION['iduser']);
    }

    /**
     * Ottiene le password per servizio
     */
    public function getPasswordsByService($service_id)
    {
        return $this->entry->selectPasswordsByService($service_id, $_SESSION['iduser']);
    }

    /**
     * Ottiene una password specifica
     */
    public function getPasswordById($id)
    {
        return $this->entry->selectPasswordById($id, $_SESSION['iduser']);
    }

    /**
     * Cerca password
     */
    public function searchPasswords($search)
    {
        return $this->entry->searchPasswords($search, $_SESSION['iduser']);
    }

    /**
     * Ottiene il conteggio delle password per servizio
     */
    public function getPasswordCountByService($service_id)
    {
        return $this->service->countPasswordsByService($service_id, $_SESSION['iduser']);
    }

    /**
     * Raggruppa le password per servizio
     */
    public function getPasswordsGroupedByService()
    {
        $passwords = $this->getPasswords();
        $grouped = [];
        
        foreach ($passwords as $password) {
            $serviceName = $password['service_name'];
            if (!isset($grouped[$serviceName])) {
                $grouped[$serviceName] = [];
            }
            $grouped[$serviceName][] = $password;
        }
        
        return $grouped;
    }
}
?> 