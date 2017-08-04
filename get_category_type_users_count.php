<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic/generic_functions.php');require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");
include('generic/auth_control.php');

if (auth_control() !==1){
    exit();
}

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$user_type_klass = !empty($_GET['user_type_1']) ? $_GET['user_type_1'] : null;
$user_type_subscriber = !empty($_GET['user_type_2']) ? $_GET['user_type_2'] : null;
$user_type_survey = !empty($_GET['user_type_5']) ? $_GET['user_type_5'] : null;
$user_type_comment = !empty($_GET['user_type_6']) ? $_GET['user_type_6'] : null;

if (!$category_id || (!$user_type_klass && !$user_type_subscriber && !$user_type_survey && !$user_type_comment)) {
    return;
}


$result = get_category_type_users_count_collections($category_id,$user_type_klass, $user_type_subscriber, $user_type_survey, $user_type_comment);


    echo $result;