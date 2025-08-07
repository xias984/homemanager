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
            case 'register':
            case 'login':
            case 'forgot':
                redirect($page);
                break;
            case 'dashboard':
            case 'settings':
            case 'newtransaction':
            case 'financeprospect':
            case 'financepayment':
            case 'analisipwb':
            case 'passwordmanager':
                redirect('login', $page);
                break;
            case 'listusers':
            case 'configuration':
            case 'financecategory':
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

function refreshPage($url = null)
{
    $url = $url ?: $_SERVER['REQUEST_URI'];
    return preg_replace('/&.*$/', '', $url);
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

$monthsList = array(
    '01' => array('January','Gennaio'),
    '02' => array('February','Febbraio'),
    '03' => array('March','Marzo'),
    '04' => array('April','Aprile'),
    '05' => array('May','Maggio'),
    '06' => array('June','Giugno'),
    '07' => array('July','Luglio'),
    '08' => array('August','Agosto'),
    '09' => array('September','Settembre'),
    '10' => array('October','Ottobre'),
    '11' => array('November','Novembre'),
    '12' => array('December','Dicembre')
    );

/**
 * Ottiene il nome del mese in italiano
 * @param string $monthNumber Numero del mese (01-12)
 * @return string Nome del mese in italiano
 */
function getMonthName($monthNumber) {
    global $monthsList;
    return isset($monthsList[$monthNumber]) ? $monthsList[$monthNumber][1] : '';
}

/**
 * Ottiene il nome del mese in inglese
 * @param string $monthNumber Numero del mese (01-12)
 * @return string Nome del mese in inglese
 */
function getMonthNameEnglish($monthNumber) {
    global $monthsList;
    return isset($monthsList[$monthNumber]) ? $monthsList[$monthNumber][0] : '';
}

/**
 * Crea un array di mesi con anno per i filtri
 * @param array $data Array di dati con date
 * @param string $dateField Campo contenente la data
 * @return array Array associativo con formato 'm-Y' => 'Mese Anno'
 */
function createMonthsWithYear($data, $dateField = 'paymentdate') {
    global $monthsList;
    
    $monthYearArray = [];
    foreach ($data as $item) {
        $monthYearArray[] = date('m-Y', strtotime($item[$dateField]));
    }
    $monthYears = array_unique($monthYearArray);
    
    $monthsWithNames = [];
    foreach ($monthYears as $monthYear) {
        list($month, $year) = explode('-', $monthYear);
        $monthName = getMonthName($month);
        $shortYear = substr($year, -2);
        $monthsWithNames[$monthYear] = $monthName . ' ' . $shortYear;
    }
    
    return $monthsWithNames;
}

function fetchDataFromApi($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return "cURL Error: $error";
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    } else {
        return "Error decoding JSON: " . json_last_error_msg();
    }
}
?>