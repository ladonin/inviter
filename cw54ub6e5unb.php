<?php

    if (isset($_REQUEST['LMI_PREREQUEST']) && $_REQUEST['LMI_PREREQUEST'] == 1){
            echo "YES";
            exit();
    }

error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');

//echo iconv ('windows-1251', 'utf-8', urldecode("%CF%EE%EF%EE%EB%ED%E5%ED%E8%E5+%E1%E0%EB%E0%ED%F1%E0")); => LMI_PAYMENT_DESC, LMI_SYS_TRANS_DATE

$string_data = file_get_contents('php://input');


$client_code = $_POST['CLIENT_CODE'];
$amount = (int)$_POST['LMI_PAYMENT_AMOUNT'];

if (!$client_code || !$string_data){
    exit();
}

$stmt = $connect->prepare("INSERT into users_pays (
                data,
                client_code,
                amount,
                created
            ) VALUES(
                :data,
                :client_code,
                :amount,
                '".time()."'
            )");
$stmt->execute(array(
    'data'          => $string_data,
    'client_code'   => $client_code,
    'amount'        => $amount,
));



$stmt = $connect->prepare("UPDATE users SET balance = (balance + :amount), modified = '".time()."' WHERE client_code = :client_code");
$stmt->execute(array(
    'amount' => $amount,
    'client_code' => $client_code
));





?>