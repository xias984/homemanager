<?php
session_start();
$messages = file_get_contents('messages.json');
$messages = json_decode($messages, true);

function redirect($page, $dest = null) {
    // Se la variabile session è attiva su loggedIn redireziona sulla pagina richiesta oppure su dashboard
    if (!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 1) {
        if (!empty($dest)) {
            require_once("frontend/pages/".$dest.".php");
        } else {
            require_once("frontend/pages/dashboard.php");
        }
    } else {
        require_once("frontend/pages/".$page.".php");
    }
}

function isAdmin() {
    if (!empty($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
        return true;
    }
}

function getPage()
{
    if(isset($_GET['page']))
    {
        $page = $_GET['page'];
        switch ($page) {
            case 'logout':
                session_destroy();
                header("Location: index.php?page=login&idmsg=3");
                break;
            case 'login':
                redirect($page);
                break;
            case 'register':
                redirect($page);
                break;
            case 'forgot':
                redirect($page);
                break;
            case 'dashboard':
                redirect('login', $page);
                break;
            case 'settings':
                redirect('login', $page);
                break;
            case 'listusers':
                redirect('login', isAdmin() ? $page : 'dashboard');
                break;
            case 'configuration':
                redirect('login', isAdmin() ? $page : 'dashboard');
                break;
            default:
                require_once("frontend/pages/404.php");
        }
    } else {
        require_once("frontend/pages/login.php");
    }
}

function getMenu()
{
    if(isset($_SESSION['menu']) && !empty($_SESSION['menu']))
    {
        $menu = $_SESSION['menu'];
        if($menu == 'main') {
            require_once("frontend/layout/menu-main.php");
        } elseif ($menu == 'entry') {
            require_once("frontend/layout/menu-entry.php");
        }
    } else {
        require_once("frontend/layout/menu-main.php");
    }
}

function getSidebar()
{
    if(isset($_SESSION['sidebar']) && !empty($_SESSION['sidebar']))
    {
        $sidebar = $_SESSION['sidebar'];
        if ($sidebar == 'entry') {
            require_once("frontend/layout/sidebar-entry.php");
        }
    }
}

function refreshPageWOmsg($url = null)
{
    $url = $url ?: $_SERVER['REQUEST_URI'];
    return preg_replace('/&?idmsg=\d+/', '', $url);
}

function ensureEnvFileExists() {
    $envFileName = 'env.php';
    $sampleFileName = 'env.sample.php';

    if (!file_exists($envFileName)) {
        if (file_exists($sampleFileName)) {
            if (rename($sampleFileName, $envFileName)) {
                return "Il file $envFileName è stato creato con successo utilizzando $sampleFileName.";
            } else {
                return "Errore durante il tentativo di rinominare il file $sampleFileName in $envFileName.";
            }
        } else {
            return "Il file $sampleFileName non esiste.";
        }
    } else {
        return "Il file $envFileName esiste già.";
    }
}

function debugPrint(...$params) {
    foreach ($params as $param) {
        print("<pre>" . print_r($param, true) . "</pre>");
    }
}

function dump(...$params) {
    debugPrint(...$params);
}

function dd(...$params) {
    debugPrint(...$params);
    exit();
}
?>