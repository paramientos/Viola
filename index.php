<?php


ini_set("always_populate_raw_post_data", -1);


define("DS", DIRECTORY_SEPARATOR);
define("ROOT", realpath(dirname(__FILE__)));


/************* system classes ********************************************/
include_once ROOT . DS . "system" . DS . "routes.class.php";
include_once ROOT . DS . "system" . DS . "e.class.php";
include_once ROOT . DS . "system" . DS . "functions.php";
include_once ROOT . DS . "system" . DS . "language.class.php";
/*****************************************************************/


if (is_array(config("init.helpers"))) {
    foreach (config("init.helpers") as $helper) {
        helper($helper);
    }
}


$parse_url = parse_url($_SERVER['REQUEST_URI']);
$url = $parse_url['path'];

$find_index = strpos($url, "index.php", 0);

$route = substr($url, $find_index + 10, strlen($url));

define("VIOLA_ROUTE", $route);

/************* user classes ********************************************/
include_once ROOT . DS . "routes.php";

/*****************************************************************/


?>