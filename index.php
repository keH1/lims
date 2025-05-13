<?php

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

header("X-XSS-Protection: 1; mode=block");

session_start();

define("PROTOCOL_GENERATOR", $_SERVER['DOCUMENT_ROOT'] . '/protocol_generator/');
const PROTOCOL_PATH             = __DIR__ . '/../protocol_generator/';
const APP_PATH                  = __DIR__ . '/application/';
const URI                       = '/ulab';
const UPLOAD_DIR                = __DIR__ . '/upload';
const UPLOAD_URL                = URI . '/upload';
const PROTOCOL_GENERATOR_URL    = '/protocol_generator/';
const TEMPLATE_DIR              = __DIR__ . '/application/templates';

// добросовестный клиент
const COMPANY_GOOD = 'UF_CRM_1654574670';

const CHECK_TZ_NOT_APPROVE = -2; // проверка тз: не утверждено
const CHECK_TZ_NOT_SENT = -1; // проверка тз: не отправлено
const CHECK_TZ_APPROVE = 1; // проверка тз: утверждено
const CHECK_TZ_WAIT = 0; // проверка тз: ожидает вердикт

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAuthRequest = 
        strpos($_SERVER['REQUEST_URI'], 'login=yes') !== false || 
        isset($_POST['USER_LOGIN']) || 
        isset($_POST['AUTH_FORM']) || 
        (isset($_POST['TYPE']) && $_POST['TYPE'] === 'AUTH');
    
    if ($isAuthRequest) {
        $_SESSION['LAST_AUTH_REQUEST'] = [
            'uri' => $_SERVER['REQUEST_URI'],
            'time' => time()
        ];
        
        $redirectUri = $_SERVER['REQUEST_URI'];

        $isAuthorized = isset($_SESSION['SESS_AUTH']) && !empty($_SESSION['SESS_AUTH']['USER_ID']);
        
        if ($isAuthorized) {
            $urlParts = parse_url($redirectUri);
            if ($urlParts === false) {
                header("Location: " . URI, true, 303);
                exit;
            }
            
            $queryParams = [];
            if (isset($urlParts['query']) && !empty($urlParts['query'])) {
                parse_str($urlParts['query'], $queryParams);

                unset($queryParams['login']);
                unset($queryParams['AUTH_FORM']);
                unset($queryParams['TYPE']);
            }
            
            $newUrl = isset($urlParts['path']) ? $urlParts['path'] : URI;
            
            if (!empty($queryParams)) {
                $newUrl .= '?' . http_build_query($queryParams);
            }
            
            if (isset($urlParts['fragment']) && !empty($urlParts['fragment'])) {
                $newUrl .= '#' . $urlParts['fragment'];
            }
            
            header("Location: {$newUrl}", true, 303);
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['LAST_AUTH_REQUEST'])) {
    unset($_SESSION['LAST_AUTH_REQUEST']);
} elseif (isset($_SESSION['LAST_AUTH_REQUEST']) && 
         (time() - $_SESSION['LAST_AUTH_REQUEST']['time']) > 3600) {
    unset($_SESSION['LAST_AUTH_REQUEST']);
}

require_once(APP_PATH . 'init.php');

$app = new App;