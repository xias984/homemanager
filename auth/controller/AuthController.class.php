<?php
require("./auth/model/Auth.class.php");

class AuthController {
    private $userData;
    private $user;

    public function __construct($userData = null) {
        $this->userData = $userData;
        $this->user = new Auth();
    }

    public function validateData() {
        if (
            $this->userData['firstname'] &&
            $this->userData['familyname'] &&
            $this->userData['email'] &&
            $this->userData['password'] &&
            $this->userData['password2']
        ) {
            return true;
        }
    }

    public function register() {
        $consent = false;
        
        if (!empty($this->userData) && $this->validateData()) {
            // Controllo se ci sono utenti admin, se non ci sono procedo con la registrazione come utente admin
            $admin = $this->user->getUsers(1) ? 0 : 1;

            // Controllo se le password coincidono
            if (!empty($this->userData['password']) && !empty($this->userData['password2'])) {
                if ($this->userData['password'] == $this->userData['password2']) {
                    $this->userData['password'] = password_hash($this->userData['password'], PASSWORD_DEFAULT);
                    $consent = true;
                } else {
                    header("Location: " .refreshPage(). "&idmsg=4");
                    $consent = false;
                }
            }

            // Controllo se l'email è già presente a sistema
            if (!empty($this->userData['email']) && $this->user->checkEmailIfExist($this->userData['email'])) {
                header("Location: " .refreshPage(). "&idmsg=5");
                $consent = false;
            } else {
                $consent = true;
            }
            
            if ($consent) {
                $result = $this->user->createUser(
                    ucfirst($this->userData['firstname']), 
                    ucfirst($this->userData['familyname']), 
                    $this->userData['email'], 
                    trim($this->userData['password']), 
                    $admin
                );

                if ($result) {
                    header("Location: index.php?page=login&idmsg=2");
                } else {
                    header("Location: index.php?page=register&idmsg=6");
                }
            }
        }
    }

    public function login() {
        // controlla se è presente l'email
        if (!empty($this->userData)) {
            if ($this->user->checkEmailIfExist($this->userData['email'])) {
                // controlla se la password è giusta
                if ($this->user->checkPassword($this->userData['email'], $this->userData['password'])) {
                    // imposta un cookie per ricordare l'accesso alla sessione
                    // se è tutto ok registra dati sessione e fai l'accesso
                    $sessionName = $this->user->getInfoUserByEmail($this->userData['email']);
                    $_SESSION['name'] = $sessionName['firstname'] . ' ' . $sessionName['familyname'];
                    $_SESSION['iduser'] = $sessionName['id'];
                    $_SESSION['loggedIn'] = 1;
                    $_SESSION['menu'] = 'entry';
                    $_SESSION['sidebar'] = 'entry';
                    $_SESSION['isAdmin'] = $sessionName['admin'] ?? true;
                    header("Location: index.php?page=dashboard&idmsg=1");
                } else {
                    header("Location: " .refreshPage(). "&idmsg=8");
                }
            } else {
                header("Location: index.php?idmsg=7");
            }
        }
    }

    public function resetPassword() {
        //$newPassword = $this->generateRandomPassword();
        $newPassword = 'password';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        if (!empty($_POST['email']) && isset($_POST['email'])) {
            $userId = $this->user->getInfoUserByEmail($_POST['email'])['id'];
            if ($this->user->updatePasswordById($userId, $hashedPassword)) {
                header("Location: " .refreshPage() . "&idmsg=10");
            } else {
                header("Location: " .refreshPage() . "&idmsg=11");
            }
        }

        return $newPassword;
    }

    private function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    public function verifyPasswordDB() {
        $hashedOldPassword = $this->user->getPasswordById()['password'];
        if (password_verify($this->userData['oldpassword'], $hashedOldPassword)) {
            // Controllo se le password coincidono
            if (!empty($this->userData['newpassword']) && !empty($this->userData['newpassword2'])) {
                if ($this->userData['newpassword'] == $this->userData['newpassword2']) {
                    $this->userData['newpassword'] = password_hash($this->userData['newpassword'], PASSWORD_DEFAULT);
                    $this->user->updatePasswordById($_SESSION['iduser'], $this->userData['newpassword']);
                    header("Location: " . refreshPage(). "&idmsg=13");
                } else {
                    header("Location: " .refreshPage(). "&idmsg=4");
                    return false;
                }
            }
        } else {
            header("Location: " .refreshPage(). "&idmsg=12");
        }
    }

    public function listUserTable() {
        $configTable = new ConfigurationController();
        $userList = array(
            array('Nome', 'Cognome', 'Email', 'Admin', 'Actions') // Intestazione
        );
        
        $userArray = array();
        if ($this->user->getUsers()) {
            $usersData = $this->user->getUsers();
            foreach ($usersData as $userData) {
                $userArray[] = [ 
                    ucfirst($userData['firstname']), 
                    ucfirst($userData['familyname']), 
                    strtolower($userData['email']), 
                    $userData['admin'],
                    $userData['id']
                ];
            }
            $userList = array_merge($userList, $userArray);
        }
    
        return $userList;
    }

    /**
     * Ottiene la lista utenti con paginazione e ordinamento lato database
     */
    public function listUserTablePaginated($params = []) {
        $result = $this->user->getUsersPaginated($params);
        
        $userList = array(
            array('Nome', 'Cognome', 'Email', 'Admin', 'Actions') // Intestazione
        );
        
        $userArray = array();
        if ($result['data']) {
            foreach ($result['data'] as $userData) {
                $userArray[] = [ 
                    ucfirst($userData['firstname']), 
                    ucfirst($userData['familyname']), 
                    strtolower($userData['email']), 
                    $userData['admin'],
                    $userData['id']
                ];
            }
            $userList = array_merge($userList, $userArray);
        }
    
        return [
            'data' => $userList,
            'pagination' => $result['pagination']
        ];
    }

    public function removeUser() {
        if (!empty($this->userData['deleteid']) && isset($this->userData['deleteid'])) {
            if ($this->user->deleteUserById($this->userData['deleteid'])) {
                $idmsg = 24;
            } else {
                $idmsg = 25;
            }
            header("Location: " . refreshPage() . "&idmsg=" . $idmsg);
        }
    }

    public function editUser($userPost) {
        dump($userPost);
        if (!empty($this->userData['editid']) && isset($this->userData['editid'])) {
            $userPost[3] = !empty($userPost[3]) ? 1 : 0;
            
            if ($this->user->updateUserById($this->userData['editid'], $userPost)) {
                header("Location: " . refreshPage() . "&idmsg=26");
            }
        }
    }
}