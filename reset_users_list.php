<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');

require_once('generic/generic_functions.php');require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");

if (auth_control() !== 1) {
    exit();
}
require('generic/show_users.php');
