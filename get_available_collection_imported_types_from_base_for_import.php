<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');

require_once('generic/generic_functions.php');require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");

if (auth_control() !==1){
    exit();
}

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

if (!$user_id) {
    return;
}


$result = get_available_collection_imported_types_from_base_for_import($category_id);
if (!$result){
    write_to_log('Категория "' . get_category_name($category_id) . '" - пустая для пользователя #' . $user_id);
}
echo(json_encode($result));