<?php
require("./auth/model/Auth.class.php");

class AuthController {
    private $userData;
    private $user;

    public function __construct($userData) {
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
            // Controllo se le password coincidono
            if (!empty($this->userData['password']) && !empty($this->userData['password2'])) {
                if ($this->userData['password'] == $this->userData['password2']) {
                    $this->userData['password'] = password_hash($this->userData['password'], PASSWORD_DEFAULT);
                    $consent = true;
                } else {
                    header("Location: " .refreshPageWOmsg(). "&idmsg=4");
                    $consent = false;
                }
            }

            // Controllo se l'email è già presente a sistema
            if (!empty($this->userData['email']) && $this->user->checkEmailIfExist($this->userData['email'])) {
                header("Location: " .refreshPageWOmsg(). "&idmsg=5");
                $consent = false;
            } else {
                $consent = true;
            }
            
            // Assegno valore booleano al campo admin access
            $this->userData['admin'] = (!empty($this->userData['admin']) && $this->userData['admin'] == 'on') ? 1 : 0;
            
            if ($consent) {
                $result = $this->user->createUser(
                    ucfirst($this->userData['firstname']), 
                    ucfirst($this->userData['familyname']), 
                    $this->userData['email'], 
                    trim($this->userData['password']), 
                    $this->userData['admin']
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
                    header("Location: " .refreshPageWOmsg(). "&idmsg=8");
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
                header("Location: " .refreshPageWOmsg() . "&idmsg=10");
            } else {
                header("Location: " .refreshPageWOmsg() . "&idmsg=11");
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
                    header("Location: " . refreshPageWOmsg(). "&idmsg=13");
                } else {
                    header("Location: " .refreshPageWOmsg(). "&idmsg=4");
                    return false;
                }
            }
        } else {
            header("Location: " .refreshPageWOmsg(). "&idmsg=12");
        }
    }
}