<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/functions.php');

if (auth_control() !==1){
    exit();
}

$category_id = !empty($_GET['category_id']) ? $_GET['category_id'] : null;


if (!$category_id || !$user_id) {
    return;
}


$result = get_client_imported_enabled_types_by_category($category_id);

echo(json_encode($result));