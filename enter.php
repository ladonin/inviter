<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Вход';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');

$register = !empty($_GET['register']) ? $_GET['register'] : null;
require_once('generic/generic_functions.php');
if (auth_control() === 1) {
        header("Location: /main");
        exit();
}
include('generic/header.php');









$cur_page = 'main';
include('generic/enter.php');

include('generic/footer.php'); ?>