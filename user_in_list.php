<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');

require_once('generic/generic_functions.php');
require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");
include('generic/auth_control.php');










require('generic/prepare_users_list_result.php');















ob_start();
foreach ($result as $user) {
    $push_link = false;
    require('generic/user_in_list.php');
    $_SESSION['infinity_scroll']['my_users_list']['viewed_users'][$load_type_status_name][$user['id']] = $user['id'];
}

$html = ob_get_clean();


$result = array('html' => $html, 'list_empty' => (empty($_SESSION['infinity_scroll']['my_users_list']['viewed_users'][$load_type_status_name]) ? 1 : 0), 'count' => $count);
echo json_encode($result);
