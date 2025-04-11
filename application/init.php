<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

spl_autoload_register(function ($class) {
    $folder = str_replace('Controller', '', $class);

    if ( file_exists(APP_PATH . "modules/{$folder}/Controller/{$class}.php") ) {
        include APP_PATH . "modules/{$folder}/Controller/{$class}.php";
    } else if ( file_exists(APP_PATH . "modules/{$folder}/Model/{$class}.php") ) {
        include APP_PATH . "modules/{$folder}/Model/{$class}.php";
    } else {
        // редирект на страницу с ошибкой
    }
});

if (!function_exists('array_key_first')) {
	function array_key_first(array $arr) {
		foreach($arr as $key => $unused) {
			return $key;
		}
		return NULL;
	}
}

require_once(APP_PATH . 'config/config.php');

require_once(APP_PATH . 'core/App.php');
require_once(APP_PATH . 'core/Controller.php');

require($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/phpqrcode/qrlib.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/protocol_generator/Morphos-master/vendor/autoload.php";
require($_SERVER['DOCUMENT_ROOT'] . '/protocol_generator/converter/index.php');

require_once(APP_PATH . '/include/StringHelper.php');
require_once(APP_PATH . '/include/DateHelper.php');

require_once(APP_PATH . 'core/Registry.php');
require_once(APP_PATH . 'core/Model.php');



